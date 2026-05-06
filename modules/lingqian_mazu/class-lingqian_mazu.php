<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Lingqian_mazu extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'lingqian_mazu';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_lingqian_mazu';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Lingqian/mazu';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":23,"mazu":{"title":"妈祖天后灵签第二十三签：丁辰(属火利夏宜其南方)","content":{"描述":"欲去长江水阔茫，前途未遂运未通；如今丝纶常在手，只恐鱼水不相逢。","解签":"要想到长江去垂钓，但只见汪洋一片，使人感到前途茫茫，不知何去何从？手拿着的钓竿长线痴痴的等待。只恐怕鱼儿与水无缘不会来了！这是一首比喻的签诗鱼水，用来形容君臣之相得，也用来比喻夫妇相爱。用垂钓象征求取得名利。","运势":"所以这首签如果问功名，有怀才不遇之惑，还要等待时机，目前恐怕难有被重用的机会；问财利目前尚欠理想；问出外无利可图，移居改换环境也许不错，问婚姻如果你有重意的对象就钩住吧，莫等待让鱼儿都跑掉了；张君年届不惑尚未成家，乃住某妈祖庙求签抽得此签。佘问：“有无对象?”答：“无。”佘曰：“就登报征婚吧，不垂钓那来鱼儿？”张君照所示登报征婚，果然不多久就接到张君请喝喜酒的帖子了。"}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/mazu.png"}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":48,"mazu":{"title":"媽祖靈簽四八簽：辛亥(屬金利秋宜西方)","content":{"描述":"陰陽作事本和同，雲遮月色正朦朧；心中欲向前途去，只恐前途路不通．","解签":"因為你做事太有個性，不能夠與人和合同流，所以迄今沒有騰達的機會，就像那明月被烏雲遮住而無光。你心裏雖想向前邁進，只恐怕你的運氣還不是亨通的時候！有才幹的人，通常多自負，眼高而手低，因此多有懷才不遇的遭遇。","运势":"這首簽詩正告訴當事人，不要過於自負。潔身自愛，固然應該，但如矯正過正，自鳴清高，往往變成好高鶩遠，理想過高，以致於遲變以往的作風，否則恐怕此去的運氣還是照樣不通順。此簽問謀事求財，須能吃苦耐勞，否則無利難成。問功名，尚不能如願，多充實自己。問婚姻，不是個理想之時機，問訴訟無結局。"}},"image":"https:\/\/yuanfenju.com\/Public\/img\/lingqian\/mazu.png"}}';

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