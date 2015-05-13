<?php

namespace Symnedi\SymfonyBundlesExtension\Tests\ElasticsearchBundle\Document;

use ONGR\ElasticsearchBundle\Annotation as ES;
use ONGR\ElasticsearchBundle\Document\AbstractDocument;


/**
 * @ES\Document
 */
class Customer extends AbstractDocument
{

	/**
	 * @ES\Property(name="name", type="string")
	 * @var string
	 */
	private $name;

}
