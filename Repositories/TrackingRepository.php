<?php
namespace Modules\Sale\Repositories;

use Modules\Sale\Trackingmore;

/**
 * Class TrackingRepository
 * @package Modules\Sale\Repositories
 */
class TrackingRepository
{
    /***********************************tracking 相关***********************************************************/
    /*
     * 列出所有运输商以及在TrackingMore系统中相应运输商简码
    * */
    /**
     * @return array|mixed|Trackingmore
     */
    public function queryCarrierList()
    {
        $track = new Trackingmore;
        $track = $track->getCarrierList();
        return $track;
    }

    /**
     *Detect a carrier by tracking code
     */
    public function detectCarrier($trackingNumber)
    {
        $track = new Trackingmore;
        $track = $track->detectCarrier($trackingNumber);
        return $track;
    }

    /**
     *获取多个运单号的物流信息
     */
    public function queryTrackingsList($numbers)
    {
        $track = new Trackingmore;
        //$numbers = 'RG848383345CN,RM121546236CN';
        $orders = '#123';
        $page = 1;
        $limit = 50;
        $createdAtMin = time() - 7*24*60*60;
        $createdAtMax = time();
        $update_time_min = time() - 7*24*60*60;
        $update_time_max = time();
        $order_created_time_min = time() - 7*24*60*60;
        $order_created_time_max = time();
        $lang = 'en';
        $track = $track->getTrackingsList($numbers,$orders,$page,$limit,$createdAtMin,$createdAtMax,$update_time_min,$update_time_max,$order_created_time_min,$order_created_time_max,$lang);
        return $track;
    }

    /**
     *创建单个运单号
     */
    public function createTracking($order_id ,$carrierCode , $trackingNumber )
    {
        $track = new Trackingmore;
        $extraInfo                         = array();
//        $extraInfo['title']                = 'iphone6';
//        $extraInfo['logistics_channel']   = '4PX挂号小包';
//        $extraInfo['customer_name']        = 'charse chen';
//        $extraInfo['customer_email']       = 'chasechen@gmail.com';
        $extraInfo['order_id']             =  $order_id;
//        $extraInfo['customer_phone']       = '86 13873399982';
//        $extraInfo['order_create_time']    = '2018-05-11 12:00';
//        $extraInfo['destination_code']     = 'US';
        $extraInfo['tracking_ship_date']   = time();
//        $extraInfo['tracking_postal_code'] = '13ES20';
        $extraInfo['lang']                 = 'en';
        $track = $track->createTracking($carrierCode,$trackingNumber,$extraInfo);
        return $track;
    }

    /**
     * @param $data
     */
    public function createMultipleTracking($data)
    {
        $track = new Trackingmore;
//        $items = array(
//            array(
//                'tracking_number' => 'RM131516216CN',
//                'carrier_code'    => 'china-post',
//                'title'          => 'iphone6',
//                'logistics_channel' => '4PX挂号小包',
//                'customer_name'   => 'charse chen',
//                'customer_email'  => 'chasechen@gmail.com',
//                'order_id'      => '8988787987',
//                'customer_phone'      => '+86 13873399982',
//                'order_create_time'      => '2018-05-11 12:00',
//                'destination_code'      => 'US',
//                'tracking_ship_date'      => time(),
//                'tracking_postal_code'      => '13ES20',
//                'lang'      => 'en'
//            ),
//            array(
//                'tracking_number' => 'RM111516216CN',
//                'carrier_code'    => 'china-post',
//                'title'          => 'iphone6s',
//                'logistics_channel' => '4PX挂号小包',
//                'customer_name'   => 'clooney chen',
//                'customer_email'  => 'clooneychen@gmail.com',
//                'order_id'      => '898874587',
//                'customer_phone'      => '+86 13873399982',
//                'order_create_time'      => '2018-05-11 12:00',
//                'destination_code'      => 'US',
//                'tracking_ship_date'      => time(),
//                'tracking_postal_code'      => '13ES20',
//                'lang'      => 'en'
//            ),
//        );
        $items = $data;
        $track = $track->createMultipleTracking($items);
        return $track;
    }

    /**
     * @param $carrierCode
     * @param $trackingNumber
     * @param $lang
     * @return array|Trackingmore
     */
    public function getSingleTrackingResult($carrierCode, $trackingNumber, $lang='en')
    {
        $track = new Trackingmore;
        $track = $track->getSingleTrackingResult($carrierCode,$trackingNumber,$lang);
        return $track;
    }

    /**
     * @param $carrierCode
     * @param $trackingNumber
     * @return array|Trackingmore
     */
    public function deleteTrackingItem($carrierCode, $trackingNumber)
    {
        $track = new Trackingmore;
        $track = $track->deleteTrackingItem($carrierCode,$trackingNumber);
        return $track;
    }

    /**
     * @param $carrierCode
     * @param $trackingNumber
     * @param $orderId
     * @param $lang
     * @return array|Trackingmore
     */
    public function getRealtimeTrackingResults($carrierCode, $trackingNumber, $orderId, $lang='en')
    {
        $track = new Trackingmore;
//        $extraInfo['destination_code']          = 'US';
//        $extraInfo['tracking_ship_date']  = '20180226';
//        $extraInfo['tracking_postal_code'] = '13ES20';
//        $extraInfo['specialNumberDestination']       = 'US';
        $extraInfo['order']       = $orderId;
//        $extraInfo['order_create_time']       = '2017/8/27 16:51';
        $extraInfo['lang']       = $lang;
        $track = $track->getRealtimeTrackingResults($carrierCode,$trackingNumber,$extraInfo);
        return $track;
    }

    /**
     * @param $data
     * @return array|Trackingmore
     */
    public function deleteMultipleTracking($data)
    {
        $track = new Trackingmore;
//        $date = array();
//        $data[] = array(
//            "tracking_number"=>"RM131516216CN",
//            "carrier_code"=>"china-post"
//        );
//        $data[] = array(
//            "tracking_number"=>"RM111516216CN",
//            "carrier_code"=>"china-post"
//        );
        $track = $track->deleteMultipleTracking($data);
        return $track;
    }

    /**
     * @param $trackingNumber
     * @param $carrierCode
     * @param $updateCarrierCode
     * @return array|Trackingmore
     */
    public function updateCarrierCode($trackingNumber, $carrierCode, $updateCarrierCode)
    {
        $track = new Trackingmore;
        $track = $track->updateCarrierCode($trackingNumber,$carrierCode,$updateCarrierCode);
        return $track;
    }

    /**
     * @return array|Trackingmore
     * 查看不同状态快递数量
     */
    public function getStatusNumberCount()
    {
        $track = new Trackingmore;
        $track = $track->getStatusNumberCount();
        return $track;
    }

    /**
     * @param $data
     * @return array|Trackingmore
     */
    public function setNumberNotUpdate($data)
    {
        $track = new Trackingmore;
//        $date = array();
//        $data[] = array(
//            "tracking_number"=>"LS465041915CN",
//            "carrier_code"=>"china-post"
//        );
//        $data[] = array(
//            "tracking_number"=>"123206167205",
//            "carrier_code"=>"gls"
//        );
        $track = $track->setNumberNotUpdate($data);
        return $track;
    }
    /*
     * 查询收货地址是否偏远
     * */
    /**
     * @param $data
     * @return array|Trackingmore
     */
    public function searchDeliveryIsRemote($data)
    {
        $track = new Trackingmore;
        $date = array();
        $data[] = array(
            "country"=>"CN",
            "postcode"=>"400422"
        );
        $data[] = array(
            "country"=>"CN",
            "postcode"=>"412000"
        );
        $track = $track->searchDeliveryIsRemote($data);
        return $track;
    }

    /**
     * @param $data
     * @return array|Trackingmore
     */
    public function getCarrierCostTime($data)
    {
        $track = new Trackingmore;
//        $data = array();
//        $data[] = array(
//            "original"=>"CN",
//            "destination"=>"US",
//            "carrier_code"=>"dhl"
//        );
//        $data[] = array(
//            "original"=>"CN",
//            "destination"=>"RU",
//            "carrier_code"=>"dhl"
//        );
        $track = $track->getCarrierCostTime($data);
        return $track;
    }
}