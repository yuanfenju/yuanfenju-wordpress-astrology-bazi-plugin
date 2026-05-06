<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Zhanbu_yaogua extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'zhanbu_yaogua';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_zhanbu_yaogua';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Zhanbu/yaogua';      // 3. 配置对应的真实 API 接口

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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"id":35,"common_desc1":"火地晋（晋卦） 求进发展 中上卦","common_desc2":"锄地锄去苗里草，谁想财帛将人找，一锄锄出银子来，这个运气也算好。","common_desc3":"异卦（下坤上离）相叠。离为日，为光明；坤为地。太阳高悬，普照大地，大地卑顺，万物生长，光明磊落，柔进上行，喻事业蒸蒸日上。","shiye":"顺利。应遵守正道，迎难而上，克敌制胜，因势利导。树立良好的人际关系，深得人心。全力以赴，不得有丝毫犹豫不决，更忌优柔寡断，而应败不馁，勇往直前。注意和衷共济，共同前进。","jingshang":"行情好。市场竞争顺利。但也会遇到一些困难，必迎难而上，因势利导，克敌制胜。争取众人支持。前进中的挫折不可免，只要动机纯正，必可转危为安。","qiuming":"经刻苦努力与奋斗，已具备开拓事业的基础，却因无人引荐，暂时不得志，决不可因此自暴自弃，须耐心等待时机。同时，更加积极地创造条件。","waichu":"克服犹豫心理，大胆前进，可无往而不顺。","hunlian":"吉星高照。会有理想的结果，但决不可自恃条件优越而抱无所谓的态度或过于挑剔。","juece":"处于不断上升的形势，不会有过大的阻力。但务必争取众人信任，获取人心，再接再厉，只要动机纯正，克服侥幸心理，必有喜从天降。","image":"https:\/\/yuanfenju.com\/Public\/img\/zhouyi64gua\/35.jpg"}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"請求成功","notice":"本次測算結果僅供娛樂使用，請勿用於封建迷信和違法用途。","data":{"id":35,"common_desc1":"火地晉（晉卦） 求進發展 中上卦","common_desc2":"鋤地鋤去苗裏草，誰想財帛將人找，一鋤鋤出銀子來，這個運氣也算好。","common_desc3":"異卦（下坤上離）相疊。離為日，為光明；坤為地。太陽高懸，普照大地，大地卑順，萬物生長，光明磊落，柔進上行，喻事業蒸蒸日上。","shiye":"順利。應遵守正道，迎難而上，克敵製勝，因勢利導。樹立良好的人際關系，深得人心。全力以赴，不得有絲毫猶豫不決，更忌優柔寡斷，而應敗不餒，勇往直前。註意和衷共濟，共同前進。","jingshang":"行情好。市場競爭順利。但也會遇到一些困難，必迎難而上，因勢利導，克敵製勝。爭取眾人支持。前進中的挫折不可免，只要動機純正，必可轉危為安。","qiuming":"經刻苦努力與奮鬥，已具備開拓事業的基礎，卻因無人引薦，暫時不得誌，決不可因此自暴自棄，須耐心等待時機。同時，更加積極地創造條件。","waichu":"克服猶豫心理，大膽前進，可無往而不順。","hunlian":"吉星高照。會有理想的結果，但決不可自恃條件優越而抱無所謂的態度或過於挑剔。","juece":"處於不斷上升的形勢，不會有過大的阻力。但務必爭取眾人信任，獲取人心，再接再厲，只要動機純正，克服僥幸心理，必有喜從天降。","image":"https:\/\/yuanfenju.com\/Public\/img\/zhouyi64gua\/35.jpg"}}';

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