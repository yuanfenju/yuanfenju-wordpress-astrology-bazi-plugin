<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Zhanbu_meiri extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'zhanbu_meiri';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_zhanbu_meiri';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Zhanbu/meiri';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"number":625,"guaming":"小吉","description":{"卦曰":"人来喜时，属水六合，谋事一五七，贵人西南，冲犯东方，大人无主家神，小孩婆祖六畜惊。","解曰":"小吉最吉昌，路上好商量，阴人来报喜，失物在坤方，行人即便至，交关甚是强，凡事皆和合，病者叩穹苍。","凶吉":"卜到小吉为吉卦，代表凡事皆吉，但是不如大安的安稳也不如速喜快速，而是介于两者中间。","运势":"目前运势不错，保持目前状况就会越来越好。","财富":"求财可得，而且有因人得财之兆。 ","感情":"若没有感情，则可因他人介绍而得。若有感情，则恋情顺利。","事业":"工作不错，但须注意处理公司财务之事，以及与下属沟通之事。","身体":"肝胆之疾病和消化系统，但是问题不大。小孩子被动物吓到或者被女性阴神冲犯，大人为冲犯家中祖先。","神鬼":"小孩子被动物吓到或者被女性阴神冲犯，大人为冲犯家中祖先。","行人":"人已经快到了。"}}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"number":625,"guaming":"小吉","description":{"卦曰":"人來喜時，屬水六合，謀事一五七，貴人西南，沖犯東方，大人無主家神，小孩婆祖六畜驚。","解曰":"小吉最吉昌，路上好商量，陰人來報喜，失物在坤方，行人即便至，交關甚是強，凡事皆和合，病者叩穹蒼。","凶吉":"卜到小吉為吉卦，代表凡事皆吉，但是不如大安的安穩也不如速喜快速，而是介於兩者中間。","运势":"目前運勢不錯，保持目前狀況就會越來越好。","财富":"求財可得，而且有因人得財之兆。 ","感情":"若沒有感情，則可因他人介紹而得。若有感情，則戀情順利。","事业":"工作不錯，但須註意處理公司財務之事，以及與下屬溝通之事。","身体":"肝膽之疾病和消化系統，但是問題不大。小孩子被動物嚇到或者被女性陰神沖犯，大人為沖犯家中祖先。","神鬼":"小孩子被動物嚇到或者被女性陰神沖犯，大人為沖犯家中祖先。","行人":"人已經快到了。"}}}';

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