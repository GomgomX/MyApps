<?php
namespace App\Classes;

class Functions
{
	public static $configs = array();

	public static function getExpForLevel($lv)
	{
		$lv--;
		$lv = (string) $lv;
		return bcdiv(bcadd(bcsub(bcmul(bcmul(bcmul("50", $lv), $lv), $lv),  bcmul(bcmul("150", $lv), $lv)), bcmul("400", $lv)), "3", 0);
	}

	public static function isValidFolderName($string)
	{
		return (strspn($string, "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789_-") == strlen($string));
	}

	public static function isValidMail($email)
	{
		return (filter_var($email, FILTER_VALIDATE_EMAIL) != false);
	}

	public function getBanReasonName($reasonId)
	{
		switch($reasonId)
		{
			case 0:
				return "Offensive Name";
			case 1:
				return "Invalid Name Format";
			case 2:
				return "Unsuitable Name";
			case 3:
				return "Name Inciting Rule Violation";
			case 4:
				return "Offensive Statement";
			case 5:
				return "Spamming";
			case 6:
				return "Illegal Advertising";
			case 7:
				return "Off-Topic Public Statement";
			case 8:
				return "Non-English Public Statement";
			case 9:
				return "Inciting Rule Violation";
			case 10:
				return "Bug Abuse";
			case 11:
				return "Game Weakness Abuse";
			case 12:
				return "Using Unofficial Software to Play";
			case 13:
				return "Hacking";
			case 14:
				return "Multi-Clienting";
			case 15:
				return "Account Trading or Sharing";
			case 16:
				return "Threatening Gamemaster";
			case 17:
				return "Pretending to Have Influence on Rule Enforcement";
			case 18:
				return "False Report to Gamemaster";
			case 19:
				return "Destructive Behaviour";
			case 20:
				return "Excessive Unjustified Player Killing";
			case 21:
				return "Invalid Payment";
			case 22:
				return "Spoiling Auction";
			default:
				return "Unknown Reason";
		}
	}
    
    public static function newCharacterNameCheck($name)
    {
        $name_to_check = strtolower($name);
        $names_blocked = array('owner','admin','princess','st', 'senior tutor','gm','cm', 'god', 'tutor', 'account manager');
        $first_words_blocked = array(' st', 'senior tutor',' admin',' princess',' owner',' gm',' cm','god','tutor','st ','admin ','princess ','owner ','gm ','cm ','god ','tutor ', "'", '-');
        $words_blocked = array("community'manager ", " community'manager", ' community manager', 'community manager ', ' community-manager', 'community-manager ', ' communitymanager', 'communitymanager ', 'community manager', 'community-manager', "community'manager", 'communitymanager', ' gamemaster', 'gamemaster ', 'gamemaster ', ' gamemaster', ' game-master', 'game-master ', "game'master ", " game'master", 'gamemaster', 'game master', 'game-master', "game'master", '  ', '--', "''","' ", " '", '- ', ' -', "-'", "'-", 'fuck', 'sux', 'suck', 'noob', 'tutor', " ' ", 'cos', 'damn', 'damn ', ' damn', 'shit', ' shit', 'shit ', 'cos ', ' cos' , 'cosomak', 'cosomak ', ' cosomak', 'god', ' god', 'god ', "'", " '", "' ", " ' ", ' sample', '-sample', "'sample", 'account ', "account'", 'account-', 'account', 'sample', ' sample ', 'sample ', ' account ');
        foreach($first_words_blocked as $word)
            if($word == substr($name_to_check, 0, strlen($word)))
                return false;
        if(substr($name_to_check, -1) == "'" || substr($name_to_check, -1) == "-")
            return false;
        if(substr($name_to_check, 1, 1) == ' ')
            return false;
        if(substr($name_to_check, -2, 1) == " ")
            return false;
        if(strlen($name) > 18)
            return false;
        if(strlen($name) < 4)
            return false;
        foreach($names_blocked as $word)
            if($word == $name_to_check)
                return false;
        foreach(config('custom.monsters') as $word)
            if($word == $name_to_check)
                return false;
        foreach(config('custom.npcs') as $word)
            if($word == $name_to_check)
                return false;
        for($i = 0; $i < strlen($name_to_check); $i++)
            if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
                return false;
        foreach($words_blocked as $word)
            if (!(strpos($name_to_check, $word) === false))
                return false;
        for($i = 0; $i < strlen($name_to_check); $i++)
            if($name_to_check[$i-1] == ' ' && $name_to_check[$i+1] == ' ')
                return false;

        return true;
    }

    public static function checkRankName($name) {
        $name = (string) $name;
        $tempName = strspn("$name", "qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM0123456789-[ ] ");
        if($tempName != strlen($name)) {
            return false;
        }

        if(strlen($name) < 3) {
            return false;
        }

        if(strlen($name) > 60) {
            return false;
        }

        return true;
    }

	public static function limitTextLength($text, $length_limit)
	{
		if(strlen($text) > $length_limit)
			return substr($text, 0, strrpos(substr($text, 0, $length_limit), " ")).'...';
		else
			return $text;
	}
	
    public static function removeBBCode($text) {
        while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false )
        {
            $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
            $text = str_ireplace('[code]'.$code.'[/code]', $code, $text);
        }
        while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false )
        {
            $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
            $text = str_ireplace('[quote]'.$quote.'[/quote]', $quote, $text);
        }
        while(stripos($text, '[url]') !== false && stripos($text, '[/url]') !== false )
        {
            $url = substr($text, stripos($text, '[url]')+5, stripos($text, '[/url]') - stripos($text, '[url]') - 5);
            $text = str_ireplace('[url]'.$url.'[/url]', $url, $text);
        }
        while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false )
        {
            echo 'test';
            $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
            $text = str_ireplace('[player]'.$player.'[/player]', $player, $text);
        }
        while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false )
        {
            $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
            $text = str_ireplace('[img]'.$img.'[/img]', $img, $text);
        }
        while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false )
        {
            $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
            $text = str_ireplace('[b]'.$b.'[/b]', $b, $text);
        }
        while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false )
        {
            $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
            $text = str_ireplace('[i]'.$i.'[/i]', $i, $text);
        }
        while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false )
        {
            $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
            $text = str_ireplace('[u]'.$u.'[/u]', $u, $text);
        }
        while(stripos($text, '[s]') !== false && stripos($text, '[/s]') !== false)
        {
            $small = substr($text, stripos($text, '[s]')+3, stripos($text, '[/s]') - stripos($text, '[s]') - 3);
            $text = str_ireplace('[s]'.$small.'[/s]', $small, $text);
        }
        while(stripos($text, '[c]') !== false && stripos($text, '[/c]') !== false)
        {
            $c = substr($text, stripos($text, '[c]')+3, stripos($text, '[/c]') - stripos($text, '[c]') - 3);
            $text = str_ireplace('[c]'.$c.'[/c]', $c, $text);
        }
        while(stripos($text, '[r]') !== false && stripos($text, '[/r]') !== false)
        {
            $r = substr($text, stripos($text, '[r]')+3, stripos($text, '[/r]') - stripos($text, '[r]') - 3);
            $text = str_ireplace('[r]'.$r.'[/r]', $r, $text);
        }
        while(stripos($text, '[*]') !== false)
        {
            $text = str_ireplace('[*]', '', $text);
        }
        return $text;
    }

    public static function canPost($account) {
        if(!$account->banned()) {
            $player = $account->getPlayers()->orderBy('level', 'DESC')->first();
            if($player) {
                if($player->level >= config('custom.forum_level_limit'))
                    return true;
            }
        }
        return false;
    }

    public static function replaceSmile($text, $smile) {
        $smileys = array(':down:' => 12, ';D' => 1, ':D' => 1, ':d' => 1, ';d' => 1, '(H)' => 2, ';o' => 3, ';O' => 3, ':o' => 3, ':O' => 3, '|-)' => 4, ':(' => 5, ';(' => 5, ':@' => 6, ';@' => 6, '8-)' => 7, ':)' => 8, ';p' => 9, ':p' => 9, ';P' => 9, ':P' => 9, ';)' => 10, ':up:' => 11, ':arr:' => 13, ':idea:' => 14, '???' => 15);
        if($smile == 1)
            return $text;
        else
        {
            foreach($smileys as $search => $replace) {
                $text = str_replace($search, '<img src="'.asset('images').'/forum/smile/'.$replace.'.gif" />', $text);
            }
            return $text;
        }
    }

    public static function replaceAll($text, $smile) {
        $rows = 0;
        while(stripos($text, '[code]') !== false && stripos($text, '[/code]') !== false && stripos($text, '[code]') < stripos($text, '[/code]'))
        {
            $code = substr($text, stripos($text, '[code]')+6, stripos($text, '[/code]') - stripos($text, '[code]') - 6);
            if(!is_int($rows / 2)) { $bgcolor = 'ABED25'; } else { $bgcolor = '23ED25'; } $rows++;
            $text = str_ireplace('[code]'.$code.'[/code]', '<i>Code:</i><br /><table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #CCCCCC; border-width: 2px"><tr><td>'.$code.'</td></tr></table>', $text);
        }
        $rows = 0;
        while(stripos($text, '[quote]') !== false && stripos($text, '[/quote]') !== false && stripos($text, '[quote]') < stripos($text, '[/quote]'))
        {
            $quote = substr($text, stripos($text, '[quote]')+7, stripos($text, '[/quote]') - stripos($text, '[quote]') - 7);
            if(!is_int($rows / 2)) { $bgcolor = 'AAAAAA'; } else { $bgcolor = 'CCCCCC'; } $rows++;
            $text = str_ireplace('[quote]'.$quote.'[/quote]', '<table cellpadding="0" style="background-color: #'.$bgcolor.'; width: 480px; border-style: dotted; border-color: #007900; border-width: 2px"><tr><td>'.$quote.'</td></tr></table>', $text);
        }
        $rows = 0;
        $text = preg_replace(array("/\[url\](.*)\[\/url\]/i", "/\[url=(.*)\](.*)\[\/url\]/i"), array("<a href=\"\\1\" target=\"_blank\">\\1</a>", "<a href=\"\\1\" target=\"_blank\">\\2</a>"), $text);
        while(stripos($text, '[player]') !== false && stripos($text, '[/player]') !== false && stripos($text, '[player]') < stripos($text, '[/player]'))
        {
            $player = substr($text, stripos($text, '[player]')+8, stripos($text, '[/player]') - stripos($text, '[player]') - 8);
            // $truePlayer = '<b>'.ucwords($player).'</b>';
            // $validator = Validator::make(['name' => $player], [
            //     'name' => ['regex:/^[\pL\s]+$/u']
            // ]);
    
            // if(!$validator->fails()) {
            //     $excludedNames = [
            //         'Account Manager',
            //         'Sorcerer Sample',
            //         'Knight Sample',
            //         'Druid Sample',
            //         'Paladin Sample'
            //     ];

            //     $loadPlayer = Player::where('name', $player)->whereNotIn('name', $excludedNames)->first();
            //     if($loadPlayer) {
            //        $truePlayer = '<a href="/community/characters/'.rawurlencode($loadPlayer->name).'">'.$loadPlayer->name.'</a>';
            //     }
            // }
            $truePlayer = '<a href="/community/characters/'.rawurlencode(ucwords($player)).'">'.ucwords($player).'</a>';
            $text = str_ireplace('[player]'.$player.'[/player]', $truePlayer, $text);
        }
        while(stripos($text, '[img]') !== false && stripos($text, '[/img]') !== false)
        {
            $img = substr($text, stripos($text, '[img]')+5, stripos($text, '[/img]') - stripos($text, '[img]') - 5);
            $text = str_ireplace('[img]'.$img.'[/img]', '<a href="'.$img.'"><img style="max-height:450;max-width:450" src="'.$img.'"></a>', $text);
        }
        while(stripos($text, '[b]') !== false && stripos($text, '[/b]') !== false && stripos($text, '[b]') < stripos($text, '[/b]'))
        {
            $b = substr($text, stripos($text, '[b]')+3, stripos($text, '[/b]') - stripos($text, '[b]') - 3);
            $text = str_ireplace('[b]'.$b.'[/b]', '<b>'.$b.'</b>', $text);
        }
        while(stripos($text, '[i]') !== false && stripos($text, '[/i]') !== false && stripos($text, '[i]') < stripos($text, '[/i]'))
        {
            $i = substr($text, stripos($text, '[i]')+3, stripos($text, '[/i]') - stripos($text, '[i]') - 3);
            $text = str_ireplace('[i]'.$i.'[/i]', '<i>'.$i.'</i>', $text);
        }
        while(stripos($text, '[s]') !== false && stripos($text, '[/s]') !== false)
        {
            $small = substr($text, stripos($text, '[s]')+3, stripos($text, '[/s]') - stripos($text, '[s]') - 3);
            $text = str_ireplace('[s]'.$small.'[/s]', '<small>'.$small.'</small>', $text);
        }
        while(stripos($text, '[u]') !== false && stripos($text, '[/u]') !== false && stripos($text, '[u]') < stripos($text, '[/u]'))
        {
            $u = substr($text, stripos($text, '[u]')+3, stripos($text, '[/u]') - stripos($text, '[u]') - 3);
            $text = str_ireplace('[u]'.$u.'[/u]', '<u>'.$u.'</u>', $text);
        }      
        while(stripos($text, '[c]') !== false && stripos($text, '[/c]') !== false)
        {
            $c = substr($text, stripos($text, '[c]')+3, stripos($text, '[/c]') - stripos($text, '[c]') - 3);
            $text = str_ireplace('[c]'.$c.'[/c]', '<center>'.$c.'</center>', $text);
        }
        while(stripos($text, '[r]') !== false && stripos($text, '[/r]') !== false)
        {
            $r = substr($text, stripos($text, '[r]')+3, stripos($text, '[/r]') - stripos($text, '[r]') - 3);
            $text = str_ireplace('[r]'.$r.'[/r]', '<s>'.$r.'</s>', $text);
        }
        while(stripos($text, '[*]') !== false)
        {
            $text = str_ireplace('[*]', '&#8226;', $text);
        }
        return self::replaceSmile($text, $smile);
    }

    public static function codeLower($text) {
        return str_ireplace(array('[b]', '[s]', '[i]', '[u]', '[c]', '[r]', '[/r][/c][/u][/i][/s][/b][s][i][u][c][r]', '[/r][/c][/u][/i][/s][r]', '[/r]', '[*]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), array('[b]', '[s]', '[i]', '[u]', '[c]', '[r]', '[/r][/c][/u][/i][/s][/b][s][i][u][c][r]', '[/r][/c][/u][/i][/s][r]', '[/r]', '[*]', '[url]', '[player]', '[img]', '[code]', '[quote]', '[/quote][/code][/url][code][quote]', '[/player]', '[/img]', '[/quote][/code][quote]', '[/quote]'), $text);
    }

    public static function showPost($topic, $text, $smile) {
        $text = nl2br($text);
        $post = '';
        if(!empty($topic)) {
            $post .= '<b>'.self::replaceSmile($topic, $smile).'</b><hr />';
        }
        $post .= self::replaceAll($text, $smile);
        return $post;
    }
}
