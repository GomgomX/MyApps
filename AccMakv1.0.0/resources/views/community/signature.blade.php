@php
if(isset($player))
{
	if(!file_exists('storage/signatures/'.$player->id.'.png') || filemtime('storage/signatures/'.$player->id.'.png') === false || filemtime('storage/signatures/'.$player->id.'.png') + 30 < time())
	{
		$image = imagecreatefrompng('./images/signatures/signature.png');
		$color= imagecolorallocate($image , 255, 255, 255);
		imagettftext($image , 12, 0, 20, 32, $color, './images/signatures/font.ttf' , 'Name:');
		imagettftext($image , 12, 0, 70, 32, $color, './images/signatures/font.ttf' , $player->name);

		imagettftext($image , 12, 0, 20, 52, $color, './images/signatures/font.ttf' , 'Level:');
		imagettftext($image , 12, 0, 70, 52, $color, './images/signatures/font.ttf' , $player->level.' '.Website::getVocationName($player->vocation));
        
		$rank_of_player = $player->rank_id;
		if(!empty($rank_of_player))
		{
			$guildRank = $player->getRank;
			if($guildRank)
			{
				$guild = $guildRank->getGuild;
				if($guild)
				{
					imagettftext($image , 12, 0, 20, 75, $color, './images/signatures/font.ttf' , 'Guild:');
					imagettftext($image , 12, 0, 70, 75, $color, './images/signatures/font.ttf' , $guildRank->name.' of the '.$guild->name);
				}
			}
		}
		imagettftext($image , 12, 0, 20, 95, $color, './images/signatures/font.ttf' , 'Last Login:');
		imagettftext($image , 12, 0, 100, 95, $color, './images/signatures/font.ttf' , (($player->lastlogin > 0) ? date("j F Y, g:i a", $player->lastlogin) : 'Never logged in.'));
		imagepng($image, 'storage/signatures/'.$player->id.'.png', 9);
		imagedestroy($image);
	}
	header("Content-type: image/png");
	echo file_get_contents('storage/signatures/'.$player->id.'.png');
}
exit();
@endphp