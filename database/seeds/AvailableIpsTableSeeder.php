<?php

use Composer\Config;
use Illuminate\Database\Seeder;
use Util\CIDR;

class AvailableIpsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table("available_ips")->insert($this->getIpsInRange(config('wireguard.WIREGUARD_CIDR_RANGE')));
    }

    private function getIpsInRange($cidrRange)
    {
        $cidr = explode("/", $cidrRange);
        $networkMask = CIDR::CIDRtoMask($cidr[1]);
        $arr = [];
        $maxPossibleHostCount = ip2long("255.255.255.255") - ip2long($networkMask);
        for ($i = 1; $i < $maxPossibleHostCount; $i++) {
            $arr[] = [
                "ip" => long2ip((ip2long($cidr[0]) & ip2long($networkMask)) | ip2long(long2ip($i))),
                "is_assigned" => false];
        }
        $serverIp = explode("/", config('wireguard.WIREGUARD_INTERFACE_IP'))[0];
        $index = array_search($serverIp, $arr);
        unset($arr[$index]);
        return $arr;
    }
}
