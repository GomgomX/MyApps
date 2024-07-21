<x-layout :$pageTitle :$subtopic>
    @php $layout_path = asset(config('custom.layout_path')); @endphp
    <center><h2>Change guild MOTD</h2></center>
    <center>You can change your guild 'message of the Day' below. This message will be displayed to all guild members when entering the guild channel.
    This is a useful way to communicate information about wars, voice chat programs and more.<BR><BR>
    <form action="/community/guilds/savemotd" method="POST">
    @csrf
    @method('patch')
    <input type="hidden" name="id" value="{{$guild->id}}"/>
    <textarea name="motd" cols="60" rows="3" maxlength="200">{!!$guild->motd!!}</textarea><br>
    <br>(Max. {{config('custom.guild_motd_chars_limit')}} chars) &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="submit" value="Update" /></form></center>
    <br><center>
    <form action="/community/guilds/manage/{{$guild->id}}" METHOD=post>
    @csrf
    <div class="BigButton" style="background-image:url({{$layout_path}}/images/buttons/sbutton.gif)" ><div onMouseOver="MouseOverBigButton(this);" onMouseOut="MouseOutBigButton(this);" >
    <div class="BigButtonOver" style="background-image:url({{$layout_path}}/images/buttons/sbutton_over.gif);" ></div>
    <input class="ButtonText" type="image" name="Back" alt="Back" src="{{$layout_path}}/images/buttons/_sbutton_back.gif" ></div></div></form></center>
</x-layout>