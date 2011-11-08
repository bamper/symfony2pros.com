<?php

namespace Proton\CoreBundle\Parser;

use Knp\Bundle\MarkdownBundle\Parser\Preset\Max;

class PurifyMarkdownParser extends Max
{

    protected $purifier;

    public function __construct(\HTMLPurifier $purifier)
    {
        parent::__construct();
        $this->purifier = $purifier;
    }

    public function transform($text)
    {
        return $this->purifier->purify(parent::transform($text));
    }

}
