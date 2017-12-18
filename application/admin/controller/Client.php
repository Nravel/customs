<?php
/**
 * Created by PhpStorm.
 * Client: clientistrator
 * Date: 2017/12/4
 * Time: 17:33
 */

namespace app\admin\controller;

use think\Loader;
use think\Validate;
use app\admin\model\Client as ClientModel;
use app\admin\model\Order as OrderModel;


class Client extends Common {

    protected $clientModel;
    protected $orderModel;
    protected $noParam = true;    //请求参数为空

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->clientModel = new ClientModel();
        $this->orderModel = new OrderModel();
        $this->clientModel->request = $this->request;
        $this->orderModel->request = $this->request;
        $this->noParam = count($this->request->param())===0;
    }

    /**
     * 客户列表
     * @return mixed
     */
    public function index() {
        if ($this->noParam) {
            return $this->fetch('client-list');
        }else{
            return $this->clientModel->getClients();
        }
    }

    /**
     * 客户添加
     * @return mixed
     */
    public function add() {
        if ($this->noParam) {
            return $this->fetch('client-add');
        }else{
            return $this->clientModel->addClient();
        }
    }

    /**
     * 客户修改
     * @return mixed
     */
    public function edit() {
        if ($this->noParam) {
            return $this->fetch('client-edit');
        }else{
            return $this->clientModel->editClient();
        }
    }

    /**
     * 客户删除
     * @return mixed
     */
    public function delClients($recover=false) {
        if ($recover) {
            return $this->clientModel->delClients($recover);
        }else{
            return $this->clientModel->delClients();
        }
    }

    /**
     * 客户搜索
     * @return mixed
     */
    public function search() {
        return $this->clientModel->search();
    }

    /**
     * 客户批次列表
     * @return mixed
     */
    public function export() {
        $pid = $this->request->param('pid');
        $com_name = $this->request->param('clientname','','rawurldecode');
        $com_id = $this->request->param('com_id');
        if ($pid === null) {
            return $this->orderModel->getOrderBatch($com_id);
        }else{
            return $this->fetch('client-batch',['pid'=>$pid,'clientname'=>$com_name]);
        }
    }

    /**
     * 客户订单列表
     * @return mixed
     */
    public function orderLists() {
        $transbatch = $this->request->param('batch');
        $batch = $this->request->param('transbatch');
        $client_name = $this->request->param('clientname','','rawurldecode');
        if ($transbatch === null) {
            return $this->orderModel->getOrderDatas($batch);
        }else{
            return $this->fetch('client-order-list',['batch'=>$transbatch,'clientname'=>$client_name]);
        }
    }

    /**
     * 删除的客户
     * @return mixed
     */
    public function delList() {
        if ($this->noParam) {
            return $this->fetch('client-del-list');
        }else{
            return $this->clientModel->getClients(true);
        }
    }


    /**
     * 客户恢复
     * @return mixed
     */
    public function recover() {
        return $this->delClients(true);
    }

    /**
     * 获取所有客户名称
     * @return array
     */
    public function getClients() {
        $res = $this->clientModel->where(['delete_status'=>0])->field('client_name')->select();
        if (count($res)>0) {
            return feedback('0000','success',$res);
        }else{
            return feedback('0005','error');
        }
    }

    /**
     *删除批次
     */
    public function delBatch() {
        return $this->clientModel->delBatch();
    }
}