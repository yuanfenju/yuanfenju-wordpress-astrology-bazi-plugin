<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Lingqian_zhuge extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'lingqian_zhuge';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_lingqian_zhuge';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Lingqian/zhuge';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":91,"zhuge":{"title":"分明壹树牡丹花、正欲开时艳若霞、忽遇妒花风雨至、摧残顷刻委泥沙。","content":{"解释":"无。","仙机":" 功名：好梦将成、忽然惊醒。 行人：被人攀留、不能即到。 婚姻：若图目下、恐不久常。 官司：若求全胜、反启祸殃。 丁口：好景不长、人丁有损。 生意：镜花水月、事事皆空。 疾病：鸟啼月落、灯烬香消。 出行：关山险阻、烟雾迷茫。 失物：物已消化、不用追寻。 田畜：莫夸繁盛、恐有天灾。 ","功名":"好梦将成、忽然惊醒。","行人":"被人攀留、不能即到。","婚姻":"若图目下、恐不久常。","官司":"若求全胜、反启祸殃。","丁口":"好景不长、人丁有损。","生意":"镜花水月、事事皆空。","疾病":"鸟啼月落、灯烬香消。","出行":"关山险阻、烟雾迷茫。","失物":"物已消化、不用追寻。","田畜":"莫夸繁盛、恐有天灾。"}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/zhuge.png"}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":11,"zhuge":{"title":"驚風駭浪失西東、一盞神燈照碧空、急向前途求解脫、上天憫惻是愚蒙。","content":{"解释":"憫惻：哀憐同情、有所不忍。 愚蒙：愚昧、不聰明、不懂事。 雁落魚沈：指書信、消息斷絕中輟。如「魚雁往返」謂書信往來以魚雁表書信。此大不同於形容女子美貌之「沈魚落雁」一辭。 亡羊補牢：原來的羊逃走了趕快修補羊圈圍欄以免再受損失比喻事後補救。 ","仙机":" 功名：欲望榮華、須當修德。 行人：雁落魚沈、相思不見。 婚姻：既多兇險、且有反復。 官司：多言多敗、多事多悔。 丁口：大小欠寧、當祈神佑。 生意：公平交易、庶保安穩。 疾病：誠心祈禱、庶保安寧。 出行：風波四起、及早回頭。 失物：亡羊補牢、尚可得半。 田畜：須防失利、難免災侵。 ","功名":"欲望榮華、須當修德。","行人":"雁落魚沈、相思不見。","婚姻":"既多兇險、且有反復。","官司":"多言多敗、多事多悔。","丁口":"大小欠寧、當祈神佑。","生意":"公平交易、庶保安穩。","疾病":"誠心祈禱、庶保安寧。","出行":"風波四起、及早回頭。","失物":"亡羊補牢、尚可得半。","田畜":"須防失利、難免災侵。"}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/zhuge.png"}}';

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