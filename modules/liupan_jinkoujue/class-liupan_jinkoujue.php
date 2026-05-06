<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Liupan_jinkoujue extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'liupan_jinkoujue';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_liupan_jinkoujue';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Liupan/jinkoujue';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"name":"测试数据","sex":"乾造","gongli":"2025年12月02日10时21分","nongli":"2025年十月十三日巳时","jieqi":"2025年11月07日12时03分立冬","xunkong":"寅卯","kongwang":"无空","yuejiang":"寅将","hangnian":"--","nianming":"--","shensha":{"guiren":"子、申","rilu":"卯","yima":"亥","taohua":"午"},"sizhu_info":{"year_gan":"乙","year_zhi":"巳","month_gan":"丁","month_zhi":"亥","day_gan":"乙","day_zhi":"巳","hour_gan":"辛","hour_zhi":"巳"},"pan_info":{"renyuan_info":{"ganzhi":"己","shuaiwang":"死"},"guishen_info":{"ganzhi":"己卯","name":"六合","shuaiwang":"旺","yongyao":""},"jiangshen_info":{"ganzhi":"丙子","name":"神后","shuaiwang":"休","yongyao":"用爻"},"difen_info":{"ganzhi":"卯","shuaiwang":"旺"}}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"name":"測試數據","sex":"乾造","gongli":"2025年12月02日10時21分","nongli":"2025年十月十三日巳時","jieqi":"2025年11月07日12時03分立冬","xunkong":"寅卯","kongwang":"無空","yuejiang":"寅將","hangnian":"--","nianming":"--","shensha":{"guiren":"子、申","rilu":"卯","yima":"亥","taohua":"午"},"sizhu_info":{"year_gan":"乙","year_zhi":"巳","month_gan":"丁","month_zhi":"亥","day_gan":"乙","day_zhi":"巳","hour_gan":"辛","hour_zhi":"巳"},"pan_info":{"renyuan_info":{"ganzhi":"丁","shuaiwang":"休"},"guishen_info":{"ganzhi":"辛巳","name":"騰蛇","shuaiwang":"休","yongyao":""},"jiangshen_info":{"ganzhi":"丙戌","name":"河魁","shuaiwang":"旺","yongyao":"用爻"},"difen_info":{"ganzhi":"丑","shuaiwang":"旺"}}}}';

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