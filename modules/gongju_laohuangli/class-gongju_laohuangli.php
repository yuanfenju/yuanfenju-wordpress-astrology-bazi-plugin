<?php
if ( ! defined( 'ABSPATH' ) ) exit;

// 注意这里的类名，要和主文件里 YFJ_Module_ + ucfirst('bazi_cesuan') 对应
class YFJ_Module_Gongju_laohuangli extends YFJ_Base_Module {

    public function __construct() {
        $this->module_id = 'gongju_laohuangli';             // 1. 修改模块 ID
        $this->shortcode = 'yfj_gongju_laohuangli';         // 2. 修改短代码
        $this->api_endpoint = '/v1/Gongju/laohuangli';      // 3. 配置对应的真实 API 接口

        parent::__construct();
    }

    /**
     * 【老黄历专属：利用 WordPress Transient API 拦截并缓存请求】
     */
    protected function fetch_api_data($api_url, $payload) {
        // 1. 获取要查询的日期作为缓存因子
        $date = $payload['title_laohuangli'] ?? '';

        // 如果没有传日期，直接放行给父类去报错
        if (empty($date)) {
            return parent::fetch_api_data($api_url, $payload);
        }

        // 2. 生成极简唯一的 Cache Key (例: yfj_hl_20260501)
        $cache_key = 'yfj_hl_' . str_replace('-', '', $date);

        // 3. 读取本地瞬态缓存
        $cached_body = get_transient($cache_key);

        if (false !== $cached_body) {
            // 缓存命中！直接返回，完全不需要向 API 服务器发网络请求
            return $cached_body;
        }

        // 4. 缓存未命中，调用基类的方法执行真实的 HTTP 请求
        $body = parent::fetch_api_data($api_url, $payload);

        // 5. 校验请求回来的数据，成功才写入缓存 (有效期改为 3 小时)
        if ($body && isset($body['errcode']) && $body['errcode'] == 0) {
            set_transient($cache_key, $body, 3 * HOUR_IN_SECONDS);
        }

        return $body;
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
        $demo_json_zh_cn = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"yangli":"2026-05-01","yinli":"二〇二六年三月十五日(农历)","ganzhi":{"yeargan":"丙","yearzhi":"午","monthgan":"壬","monthzhi":"辰","daygan":"乙","dayzhi":"亥","timegan":"壬","timezhi":"午"},"wuxing":"天河水(年) 长流水(月) 山头火(日)","chongsha":"冲(己巳)蛇煞西","baiji":"乙不栽植千株不长 亥不嫁娶不利新郎","xingxiu":"亢龙凶","xingxiuge":"亢星造作长房当，十日之中主有殃，田地消磨官失职，接运定是虎狼伤，嫁娶婚姻用此日，儿孙新妇守空房，埋葬若还用此日，当时害祸主重伤。","guishenfangwei":"阳贵神：西南 阴贵神：正北","xishenfangwei":"西北","fushenfangwei":"西南","caishenfangwei":"东北","benyuetaishen":"占门堂","jinritaishen":"碓磨床 外西南","zhixing":"危","jishen":"母仓 不将 玉宇 ","xiongshen":"游祸 天贼 四穷 八龙 重日 ","yi":"沐浴 捕捉 畋猎 结网 取渔 ","ji":"祭祀 嫁娶 入宅 作灶 安葬 ","xingqi":"星期五","liuyao":"大安","qizheng":"金","wuhou":"戴胜降于桑","gongshou":"东方青龙","yuexiang":"望","jieqi":"无","tianshen":"玉堂","huanghei":"黄道","jixiong":"吉","detail_info":[{"time_shichen":"子时","time_ganzhi":"丙子","time_region":"23:00~00:59","time_yi":"祭祀 祈福 酬神 出行 求财 见贵 订婚 嫁娶 修造 安葬 赴任","time_ji":"上梁 盖屋 入殓","time_guishenfangwei":"阳贵神：正西 阴贵神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"西南","time_chong":"(庚午)马","time_sha":"南","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"白虎","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"丑时","time_ganzhi":"丁丑","time_region":"01:00~02:59","time_yi":"祭祀 祈福 酬神 订婚 嫁娶 出行 求财 入宅 安葬 修造 盖屋 移徙 安床 赴任","time_ji":"无","time_guishenfangwei":"阳贵神：西北 阴贵神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"东南","time_caishenfangwei":"西南","time_chong":"(辛未)羊","time_sha":"东","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"玉堂","time_huanghei":"黄道","time_jixiong":"吉"},{"time_shichen":"寅时","time_ganzhi":"戊寅","time_region":"03:00~04:59","time_yi":"订婚 嫁娶 求财 开市 交易 安床","time_ji":"祈福 求嗣 赴任 修造 移徙 出行 词讼","time_guishenfangwei":"阳贵神：东北 阴贵神：正北","time_xishenfangwei":"东南","time_fushenfangwei":"东北","time_caishenfangwei":"正北","time_chong":"(壬申)猴","time_sha":"北","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"天牢","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"卯时","time_ganzhi":"己卯","time_region":"05:00~06:59","time_yi":"祈福 求嗣 订婚 嫁娶 出行 求财 开市 交易 安床 赴任","time_ji":"无","time_guishenfangwei":"阳贵神：正北 阴贵神：正北","time_xishenfangwei":"东北","time_fushenfangwei":"正北","time_caishenfangwei":"正北","time_chong":"(癸酉)鸡","time_sha":"西","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"玄武","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"辰时","time_ganzhi":"庚辰","time_region":"07:00~08:59","time_yi":"作灶 祭祀 祈福 斋醮 酬神 赴任 见贵 求财 出行 嫁娶 进人口 移徙 安葬","time_ji":"修造 动土","time_guishenfangwei":"阳贵神：正南 阴贵神：正北","time_xishenfangwei":"西北","time_fushenfangwei":"西南","time_caishenfangwei":"正东","time_chong":"(甲戌)狗","time_sha":"南","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"司命","time_huanghei":"黄道","time_jixiong":"吉"},{"time_shichen":"巳时","time_ganzhi":"辛巳","time_region":"09:00~10:59","time_yi":"无","time_ji":"诸事不宜","time_guishenfangwei":"阳贵神：东北 阴贵神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"正东","time_chong":"(乙亥)猪","time_sha":"东","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"勾陈","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"午时","time_ganzhi":"壬午","time_region":"11:00~12:59","time_yi":"求嗣 嫁娶 移徙 入宅 开市 交易 修造 安葬 订婚 见贵 求财","time_ji":"祭祀 祈福 斋醮 开光 赴任 出行","time_guishenfangwei":"阳贵神：正东 阴贵神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"东南","time_caishenfangwei":"正南","time_chong":"(丙子)鼠","time_sha":"北","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"青龙","time_huanghei":"黄道","time_jixiong":"吉"},{"time_shichen":"未时","time_ganzhi":"癸未","time_region":"13:00~14:59","time_yi":"求嗣 订婚 嫁娶 求财 开市 交易 安床 修造 盖屋 移徙 作灶","time_ji":"祭祀 祈福 斋醮 开光 赴任 出行","time_guishenfangwei":"阳贵神：东南 阴贵神：正北","time_xishenfangwei":"东南","time_fushenfangwei":"东北","time_caishenfangwei":"正南","time_chong":"(丁丑)牛","time_sha":"西","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"明堂","time_huanghei":"黄道","time_jixiong":"吉"},{"time_shichen":"申时","time_ganzhi":"甲申","time_region":"15:00~16:59","time_yi":"赴任 出行 求财 见贵","time_ji":"祭祀 祈福 斋醮 酬神 开光 修造 安葬","time_guishenfangwei":"阳贵神：西南 阴贵神：正北","time_xishenfangwei":"东北","time_fushenfangwei":"正北","time_caishenfangwei":"东北","time_chong":"(戊寅)虎","time_sha":"南","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"天刑","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"酉时","time_ganzhi":"乙酉","time_region":"17:00~18:59","time_yi":"无","time_ji":"赴任 出行 求财","time_guishenfangwei":"阳贵神：西南 阴贵神：正北","time_xishenfangwei":"西北","time_fushenfangwei":"西南","time_caishenfangwei":"东北","time_chong":"(己卯)兔","time_sha":"东","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"朱雀","time_huanghei":"黑道","time_jixiong":"凶"},{"time_shichen":"戌时","time_ganzhi":"丙戌","time_region":"19:00~20:59","time_yi":"订婚 嫁娶 开市 安葬","time_ji":"上梁 盖屋 入殓 祭祀 祈福 斋醮 酬神","time_guishenfangwei":"阳贵神：正西 阴贵神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"西南","time_chong":"(庚辰)龙","time_sha":"北","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"金匮","time_huanghei":"黄道","time_jixiong":"吉"},{"time_shichen":"亥时","time_ganzhi":"丁亥","time_region":"21:00~22:59","time_yi":"祭祀 祈福 酬神 订婚 嫁娶 求财 入宅 安葬","time_ji":"赴任 出行","time_guishenfangwei":"阳贵神：西北 阴贵神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"东南","time_caishenfangwei":"西南","time_chong":"(辛巳)蛇","time_sha":"西","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"天德","time_huanghei":"黄道","time_jixiong":"吉"}]}}';
        $demo_json_zh_tw = '{"errcode":0,"errmsg":"请求成功","notice":"本次测算结果仅供娱乐使用，请勿用于封建迷信和违法用途。","data":{"yangli":"2026-05-01","yinli":"二〇二六年三月十五日(农历)","ganzhi":{"yeargan":"丙","yearzhi":"午","monthgan":"壬","monthzhi":"辰","daygan":"乙","dayzhi":"亥","timegan":"壬","timezhi":"午"},"wuxing":"天河水(年) 長流水(月) 山頭火(日)","chongsha":"沖(己巳)蛇煞西","baiji":"乙不栽植，千株不長 亥不嫁娶，不利新郎","xingxiu":"亢龍兇","xingxiuge":"亢星造作長房當，十日之中主有殃，田地消磨官失職，接運定是虎狼傷，嫁娶婚姻用此日，兒孫新婦守空房，埋葬若還用此日，當時害禍主重傷。","guishenfangwei":"陽貴神：西南 陰貴神：正北","xishenfangwei":"西北","fushenfangwei":"西南","caishenfangwei":"東北","benyuetaishen":"占門堂","jinritaishen":"碓磨床 外西南","zhixing":"危","jishen":"母倉 不將 玉宇 ","xiongshen":"遊禍 天賊 四窮 八龍 重日 ","yi":"沐浴 捕捉 畋獵 結網 取漁 ","ji":"祭祀 嫁娶 入宅 作竈 安葬 ","xingqi":"星期五","liuyao":"大安","qizheng":"金","wuhou":"戴勝降於桑","gongshou":"東方青龍","yuexiang":"望","jieqi":"無","tianshen":"玉堂","huanghei":"黃道","jixiong":"吉","detail_info":[{"time_shichen":"子時","time_ganzhi":"丙子","time_region":"23:00~00:59","time_yi":"祭祀 祈福 酬神 出行 求財 見貴 訂婚 嫁娶 修造 安葬 赴任","time_ji":"上樑 蓋屋 入殮","time_guishenfangwei":"陽貴神：正西 陰貴神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"西南","time_chong":"(庚午)馬","time_sha":"南","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"白虎","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"丑時","time_ganzhi":"丁丑","time_region":"01:00~02:59","time_yi":"祭祀 祈福 酬神 訂婚 嫁娶 出行 求財 入宅 安葬 修造 蓋屋 移徙 安床 赴任","time_ji":"無","time_guishenfangwei":"陽貴神：西北 陰貴神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"東南","time_caishenfangwei":"西南","time_chong":"(辛未)羊","time_sha":"東","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"玉堂","time_huanghei":"黃道","time_jixiong":"吉"},{"time_shichen":"寅時","time_ganzhi":"戊寅","time_region":"03:00~04:59","time_yi":"訂婚 嫁娶 求財 開市 交易 安床","time_ji":"祈福 求嗣 赴任 修造 移徙 出行 詞訟","time_guishenfangwei":"陽貴神：東北 陰貴神：正北","time_xishenfangwei":"東南","time_fushenfangwei":"東北","time_caishenfangwei":"正北","time_chong":"(壬申)猴","time_sha":"北","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"天牢","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"卯時","time_ganzhi":"己卯","time_region":"05:00~06:59","time_yi":"祈福 求嗣 訂婚 嫁娶 出行 求財 開市 交易 安床 赴任","time_ji":"無","time_guishenfangwei":"陽貴神：正北 陰貴神：正北","time_xishenfangwei":"東北","time_fushenfangwei":"正北","time_caishenfangwei":"正北","time_chong":"(癸酉)雞","time_sha":"西","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"玄武","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"辰時","time_ganzhi":"庚辰","time_region":"07:00~08:59","time_yi":"作竈 祭祀 祈福 齋醮 酬神 赴任 見貴 求財 出行 嫁娶 進人口 移徙 安葬","time_ji":"修造 動土","time_guishenfangwei":"陽貴神：正南 陰貴神：正北","time_xishenfangwei":"西北","time_fushenfangwei":"西南","time_caishenfangwei":"正東","time_chong":"(甲戌)狗","time_sha":"南","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"司命","time_huanghei":"黃道","time_jixiong":"吉"},{"time_shichen":"巳時","time_ganzhi":"辛巳","time_region":"09:00~10:59","time_yi":"無","time_ji":"諸事不宜","time_guishenfangwei":"陽貴神：東北 陰貴神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"正東","time_chong":"(乙亥)豬","time_sha":"東","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"勾陳","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"午時","time_ganzhi":"壬午","time_region":"11:00~12:59","time_yi":"求嗣 嫁娶 移徙 入宅 開市 交易 修造 安葬 訂婚 見貴 求財","time_ji":"祭祀 祈福 齋醮 開光 赴任 出行","time_guishenfangwei":"陽貴神：正東 陰貴神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"東南","time_caishenfangwei":"正南","time_chong":"(丙子)鼠","time_sha":"北","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"青龍","time_huanghei":"黃道","time_jixiong":"吉"},{"time_shichen":"未時","time_ganzhi":"癸未","time_region":"13:00~14:59","time_yi":"求嗣 訂婚 嫁娶 求財 開市 交易 安床 修造 蓋屋 移徙 作竈","time_ji":"祭祀 祈福 齋醮 開光 赴任 出行","time_guishenfangwei":"陽貴神：東南 陰貴神：正北","time_xishenfangwei":"東南","time_fushenfangwei":"東北","time_caishenfangwei":"正南","time_chong":"(丁丑)牛","time_sha":"西","time_xun":"甲戌","time_xunkong":"申酉","time_tianshen":"明堂","time_huanghei":"黃道","time_jixiong":"吉"},{"time_shichen":"申時","time_ganzhi":"甲申","time_region":"15:00~16:59","time_yi":"赴任 出行 求財 見貴","time_ji":"祭祀 祈福 齋醮 酬神 開光 修造 安葬","time_guishenfangwei":"陽貴神：西南 陰貴神：正北","time_xishenfangwei":"東北","time_fushenfangwei":"正北","time_caishenfangwei":"東北","time_chong":"(戊寅)虎","time_sha":"南","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"天刑","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"酉時","time_ganzhi":"乙酉","time_region":"17:00~18:59","time_yi":"無","time_ji":"赴任 出行 求財","time_guishenfangwei":"陽貴神：西南 陰貴神：正北","time_xishenfangwei":"西北","time_fushenfangwei":"西南","time_caishenfangwei":"東北","time_chong":"(己卯)兔","time_sha":"東","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"朱雀","time_huanghei":"黑道","time_jixiong":"兇"},{"time_shichen":"戌時","time_ganzhi":"丙戌","time_region":"19:00~20:59","time_yi":"訂婚 嫁娶 開市 安葬","time_ji":"上樑 蓋屋 入殮 祭祀 祈福 齋醮 酬神","time_guishenfangwei":"陽貴神：正西 陰貴神：正北","time_xishenfangwei":"西南","time_fushenfangwei":"西北","time_caishenfangwei":"西南","time_chong":"(庚辰)龍","time_sha":"北","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"金匱","time_huanghei":"黃道","time_jixiong":"吉"},{"time_shichen":"亥時","time_ganzhi":"丁亥","time_region":"21:00~22:59","time_yi":"祭祀 祈福 酬神 訂婚 嫁娶 求財 入宅 安葬","time_ji":"赴任 出行","time_guishenfangwei":"陽貴神：西北 陰貴神：正北","time_xishenfangwei":"正南","time_fushenfangwei":"東南","time_caishenfangwei":"西南","time_chong":"(辛巳)蛇","time_sha":"西","time_xun":"甲申","time_xunkong":"午未","time_tianshen":"天德","time_huanghei":"黃道","time_jixiong":"吉"}]}}';

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