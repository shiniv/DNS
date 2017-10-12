<?php

/*
 * This file is part of Badcow DNS Library.
 *
 * (c) Samuel Williams <sam@badcow.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Badcow\DNS\Rdata;

class PtrRdata extends CnameRdata
{
    const TYPE = 'PTR';

    /**
     * @var string
     */
    protected $ptr;

    /**
     * @param string $ptr
     *
     * @throws RdataException
     */
    public function setAddress($ptr)
    {
        $ptr = trim($ptr);
        if(!preg_match("/\.arpa\.$/i", $ptr)) {
            throw new RdataException(sprintf('PTR "%s" invalid.', $ptr));
        }

        preg_match_all("/(.*?)(\.ip6)?\.arpa./", $ptr, $match);
        $array = array_reverse(array_values(explode(".", $match[1][0])));
        if($match[2][0] == '.ip6') {
            $address =inet_ntop(pack("H*", implode("", $array)));
            if (!Validator::validateIpv6Address($address)) {
                throw new RdataException(sprintf('PTR "%s" invalid.', $ptr));
            }
        } else {
            $address = implode(".", $array);
            if (!Validator::validateIpv4Address($address)) {
                throw new RdataException(sprintf('PTR "%s" invalid.', $ptr));
            }
        }
        
        $this->ptr = $ptr;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->ptr;
    }

    /**
     * {@inheritdoc}
     */
    public function output()
    {
        return $this->ptr;
    }
}
