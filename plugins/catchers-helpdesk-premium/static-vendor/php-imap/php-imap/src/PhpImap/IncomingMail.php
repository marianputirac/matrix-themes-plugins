<?php namespace StghPhpImap;

/**
 * @see https://github.com/barbushin/php-imap
 * @author Barbushin Sergey http://linkedin.com/in/barbushin
 */
class IncomingMail {

	public $id;
	public $date;
	public $subject;

	public $fromName;
	public $fromAddress;

	public $to = array();
	public $toString;
	public $cc = array();
	public $replyTo = array();

	public $xAutoreply;
	public $xAutoResponseSuppress;
	public $autoSubmitted;

	public $messageId;

	public $textPlain;
	public $textHtml;
	/** @var IncomingMailAttachment[] */
	public $attachments = array();

	public function addAttachment(IncomingMailAttachment $attachment) {
		$this->attachments[$attachment->id] = $attachment;
	}

	/**
	 * @return IncomingMailAttachment[]
	 */
	public function getAttachments() {
        update_post_meta(1,'attachment_function',$this->attachments);
		return $this->attachments;
	}

	/**
	 * Get array of internal HTML links placeholders
	 * @return array attachmentId => link placeholder
	 */
	public function getInternalLinksPlaceholders() {
		return preg_match_all('/=["\'](ci?d:([\w\.%*@-]+))["\']/i', $this->textHtml, $matches) ? array_combine($matches[2], $matches[1]) : array();

	}

	public function replaceInternalLinks($baseUri) {
		$baseUri = rtrim($baseUri, '\\/') . '/';
		$fetchedHtml = $this->textHtml;
        update_post_meta(1,'fetchedHtml',$fetchedHtml);
		foreach($this->getInternalLinksPlaceholders() as $attachmentId => $placeholder) {
			if(isset($this->attachments[$attachmentId])) {
				$fetchedHtml = str_replace($placeholder, $baseUri . basename($this->attachments[$attachmentId]->filePath), $fetchedHtml);
			}
		}
		return $fetchedHtml;
	}
}

class IncomingMailAttachment {

	public $id;
	public $name;
	public $filePath;
	public $disposition;
}
