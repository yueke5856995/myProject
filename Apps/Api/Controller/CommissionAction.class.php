<?php
namespace Api\Action;
class CommissionAction extends CommonAction {
    /**
     * 提現接口 佣金為0不予提現
     */
    public function tixian(){
        $commission = M('commission');
        $commission -> tixian();
    }

}