<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Lingqian_yuelao extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'lingqian_yuelao';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_lingqian_yuelao';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Lingqian/yuelao';      // 3. 配置对应的真实 API 接口

        parent::__construct();
    }

    /**
     * 专属数据拦截与加工引擎 (重写基类方法)
     * 在这里可以对 API 返回的原始数据进行格式化、过滤或追加二次计算的自定义数据。
     */
    protected function process_api_data($raw_data) {
        // 1. 确保数据是数组格式，防止报错
        if (is_array($raw_data)) {
            //如果 API 没返回格局，给个默认的兜底值
            if (!isset($raw_data['base_info']['zhengge'])) {
                $raw_data['base_info']['zhengge'] = '未入格';
            }
        }

        //将处理后的数据返回给 views/result.php 进行渲染
        return $raw_data;
    }

    /**
     * 沙盒模式下的模拟数据
     */
    protected function get_demo_data($post_data) {
        //获取当前后台设置的语言
        $lang = get_option('yfj_language', 'zh-cn');

        // 1. 把你的完整 JSON 粘贴到单引号里面（要考虑到zh-cn 和 zh-tw）
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":53,"yuelao":{"title":"其所厚者薄，而其所薄者厚。","content":{"吉凶":"上上签","解签":"世人之现象。出乎人之意料者多。如其某君之于汝。曾施予恩。报之也薄。又对某兄施与之薄。君却以厚礼还之。古人曰。施恩勿念。受惠勿忘。行善者。不必以报为念。上苍知之即足矣。唯本签之意即是。君汝之婚姻复如是。君汝之婚姻复如是。必能有意外之好姻缘来结合耶。","仙注":"","白话":""}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/yuelao.png"}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":79,"yuelao":{"title":"更灑遍客舍青青，千縷柳色新。","content":{"吉凶":"上簽","解签":"更灑遍。即是表明春雨不得。客舍青青。客舍。爾之住處。青青也。洗得好幹凈。春雨來耶。千縷柳色新。岸邊之楊柳亦條條著上新綠衣。春景已經呈現汝之眼前。爾已經可喜之時來到。","仙注":"","白话":""}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/yuelao.png"}}';

        if ($lang == 'zh-tw') {
            $demo_json = $demo_json_zh_tw;
        } else {
            $demo_json = $demo_json_zh_cn;
        }

        // 2. 解析为 PHP 数组
        $demo_array_json = json_decode($demo_json, true);

        // 3. 【核心提取】剥离外层的 errcode 状态，只提取 data 节点内容
        $data = $demo_array_json['data'] ?? [];

        // 5. 返回处理好的干净数据给 process_api_data
        return $data;
    }
}