<?php
namespace Premanager\IO;

use Premanager\Debug\Debug;
use Premanager\Premanager;
use Premanager\Execution\Template;
use Premanager\Execution\PageBlock;
use Premanager\Execution\Environment;
use Premanager\Models\Project;
use Premanager\Execution\Options;
use Premanager\Module;

/**
 * Defines an email with subject, html and plain text contents and attachments
 * which can be sent
 */
class Mail extends Module {
	/**
	 * The subject
	 * 
	 * @var string
	 */
	public $title;
	/**
	 * The plain text part of the content
	 * 
	 * @var string
	 */
	public $plainContent;
	/**
	 * An array of Premanager\IO\Attachment objects
	 * 
	 * @var array
	 */
	public $attachments = array();
	/**
	 * An array of Premanager\IO\InlineAttachment objects
	 * 
	 * @var array
	 */
	public $inlineAttachments = array();
	
	/**
	 * An array of rows of cols of blocks (Premanager\Execution\PageBlock)
	 * 
	 * A block is accessed in this pattern: $blocks[$row][$col][$index].
	 * 
	 * @var array
	 */
	public $blocks = array();
	
	/**
	 * Adds a new block in a new col in a new row. The value of the $title
	 * property is used for the block's title, the block's body will be $content
	 * and it will be a main block.
	 * 
	 * $title has to be set before calling this method
	 * 
	 * @param string $body the content for the block's body
	 */
	public function createMainBlock($body) {
		$this->blocks[] = array(array(PageBlock::createSimple(
			$this->title, $body, false, false, true)));
	}
	
	/**
	 * Adds the block in the first col of the first row and places it at the end
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to append
	 */
	public function appendBlock(PageBlock $block) {
		if (!count($this->blocks))
			$this->blocks[] = array(array());
		if (!count($this->blocks[0]))
			$this->blocks[0][] = array();
		$this->blocks[0][0][] = $block;
	}
	
	/**
	 * Adds the block in the first col of the first row and places it at the
	 * beginning
	 * 
	 * @param Premanager\Execution\PageBlock $block the block to insert
	 */
	public function insertBlock(PageBlock $block) {
		if (!count($this->blocks))
			$this->blocks[] = array(array());
		if (!count($this->blocks[0]))
			$this->blocks[0][] = array();
		$arr &= $this->blocks[0][0];
		array_splice($arr, 0, 0, array($block));
	}
	
	/**
	 * Sends this email to the specified recipent
	 * 
	 * @param string $recipentEmail the recipent's email
	 * @param string $recipentName the recipent's name (optional)
	 * @param bool $log true to log the mail header and content
	 */
	public function send($recipentEmail, $recipentName, $log = false) {
		$mainBoundary = self::generateBoundary();	
		$bodyBoundary = self::generateBoundary();
		$htmlBoundary = self::generateBoundary();
		
		$fromAddress = Options::defaultGet('Premanager', 'email');
		$fromName = Project::getOrganization()->getTitle(); 
	
		$from = "$fromName <$fromAddress>";
		
		// Include stylesheets
		$inlineAttachments = $this->inlineAttachments;
		if (!is_array($inlineAttachments))
			$inlineAttachments = array();
		$number = 0;
		foreach (Environment::getCurrent()->getStyle()->getStylesheets() as
			$stylesheet)
		{
			$inlineAttachments[] = new InlineAttachment(
				basename($stylesheet->getFileName()),
				$stylesheet->getType(),
				'stylesheet-'.$number.'@premanager',
				file_get_contents($stylesheet->getFileName()));
			$number++;
		}

		$header  = "From: $from\n";
		$header .= "MIME-Version: 1.0\n";
		$header .= "Content-Type: multipart/mixed; ".
			"boundary=\"$mainBoundary\"\n";

		$content = "This is a multi-part message in MIME format.\n\n";

		// Body (plain & html)
		$content .=
			"--$mainBoundary\n".
			"Content-Type: multipart/alternative; boundary=\"$bodyBoundary\"\n".
			"This is a multi-part message in MIME format.\n".
			"\n".
			"--$bodyBoundary\n".
			"Content-Type: text/plain; charset=\"utf-8\"; format=flowed\n".
			"Content-Transfer-Encoding: 8bit\n\n".
			$this->plainContent."\n\n".
			"--$bodyBoundary\n".
			"Content-Type: multipart/related; boundary=\"$htmlBoundary\"\n\n\n".
			"--$htmlBoundary\n".
			"Content-Type: text/html; charset=\"utf-8\"\n".
			"Content-Transfer-Encoding: 8bit\n\n".
			$this->getHTMLContent().
			"\n\n";

		// Inline Attachments
		foreach ($inlineAttachments as $attachment) {
			if (!($attachment instanceof InlineAttachment))
				break;
				
			$text = $attachment->getContent();
			$data = chunk_split(base64_encode($text));
			
			$content .=
				"--$htmlBoundary\n".
				"Content-Disposition: inline;\n".
				"\tfilename=\"".$attachment->getFileName()."\";\n".
				"Content-ID: <".$attachment->getContentID().">\n".
				"Content-Length: ".strlen($text).";\n".
				"Content-Type: ".$attachment->getContentType()."; ".
					"name=\"".$attachment->getFileName()."\"\n".
				"Content-Transfer-Encoding: base64\n\n".
				$data."\n\n";
		}
		
		$content .= "--$htmlBoundary--\n\n";
		$content .= "--$bodyBoundary--\n\n";
		
		// Attachments
		if (is_array($this->attachments))
			foreach ($this->attachments as $attachment) {
				if (!($attachment instanceof Attachment))
					break;
					
				$text = $attachment->getContent();
	
				$data = chunk_split(base64_encode($text));
				$content .=
					"--$mainBoundary\n".
					"Content-Disposition: attachment;\n".
					"\tfilename=\"".$attachment->getFileName()."\";\n".
					"Content-Length: ".strlen($text).";\n".
					"Content-Type: ".$attachment->getContentType().
						"; name=\"".$attachment->getFileName()."\"\n".
					"Content-Transfer-Encoding: base64\n\n".
					$data."\n\n";
			}

		$content .= "--$mainBoundary--\n\n";
			
		$recipentName = preg_replace('[<>\"]', '', $recipentName);
		$to = $recipentName ? "$recipentName <$recipentEmail>" : $recipentEmail;

		if ($log)
			Debug::log($header.$content);
		return mail($to, $this->title, $content, $header);
	}
	
	/**
	 * Gets the html content
	 * 
	 * @return string
	 */
	private function getHTMLContent() {
		$template = new Template('Premanager', 'mail');
		
		$template->set('title', $this->title);
		$template->set('blocks', $this->blocks);
		$template->set('environment', Environment::getCurrent());
		$template->set('organization', Project::getOrganization());
		$template->set('version', Premanager::getVersionInfo());
		
		return $template->get();
	}
	
	/**
	 * Generates a random boundary divider
	 * 
	 * @return string the boundary divider
	 */
	private static function generateBoundary() {
		return "-----=".md5(uniqid(mt_rand(), 1));
	}
}

?>
