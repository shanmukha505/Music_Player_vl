<template>

<div class="name" id="play" >

    <div class="audio" align="center" style="">

      <img class="image is-4by2" :src="image" align="center" style="margin-bottom: 2%;"/>

      <h2 style="font-size: 1rem; ">{{ this.name }} </h2>

      <div class="is-center">

        <button class="play previous" type="button"  v-on:click="playnext" title="previous"></button>

        <button class="play" v-on:click="play" title="play" :class="{'active':!active,'pause':active}"  type="button" ></button>

        <button class="play next" type="button" title="next" v-on:click="playprevious"></button>

        <input type="range" class="custom-range"  step="1" min="0" max="1000" :value="volume" @change="volumechange" ref="volume" />

      </div>

      <div class="slidecontainer">


        {{ this.currenttime }}
        <input type="range" min="0" :max="songduration" class="slider" :value="sliderValue" id="myRange" @change="changed" ref="slider">
        {{ this.duration }}

      </div>

      <div class="is-center">

          <button class="play suffle" title="suffle" v-on:click="suffle" :class="{'suffle':!disabled,'suffles':disabled}"   type="button"></button>

          <button class="play repeat" title="repeat" type="button" :class="{'repeat':!sdisabled,'repeats':sdisabled}" v-on:click="repeats"></button>

          <button class="single" title="single repeat" type="button" :class="{'single': ssingle,'singles':ssingle}" v-on:click="single"></button>

      </div>

      <button type="button" class="btn is-primary" v-on:click="some_toggle=!some_toggle">Albums</button>

    </div>

    <div v-for="song in songs" v-if="!some_toggle">

        <music :src="song.src" :song="song.name" :song_id ="song.song_id" :user_id ="song.user_id"  @selected="view"></music>

    </div>

    <album :album="reset_songs" @resetIt="resetAll" v-if="some_toggle" ></album>

    <div v-for="albums in album" v-if="some_toggle">

    <album :album="albums"></album>

    </div>

  </div>

</template>

<script>

import Music from './Music.vue';

import {Howl, Howler} from 'howler';

import './bootstrap';

import Album from './Album.vue';

import * as musicMetadata from 'music-metadata-browser';


export default {

  components:{

    Music,

    Album,

  },

  mounted()
    {


      axios.post('/isLoggedIn').then(response => {

        this.user=response.data;


      });

      axios.post('/song').then(response => {


        this.songs=response.data;
        this.reset_songs=response.data;
        this.reset_songs['album']="ALL";

        if(this.user=="")

        {

          for(var i=0;i<this.songs.length;i++)
          {

            if(this.songs[i]['visible']=='private')
            {
              this.songs.splice(i,1);

              i=-1;

            }

          }

        }
        else
        {

          for(var i=0;i<this.songs.length;i++)
          {

            if(this.songs[i]['visible']=='private' && this.songs[i]['user_id']!=this.user)
            {
              this.songs.splice(i,1);

              i=-1;
            }

          }

        }

        for(var i=0;i<this.songs.length;i++)
        {

          var src='/songFile'+this.songs[i]['src'] +'/'+ this.songs[i]['name'];

          this.readFromBlob(this.songs[i]['src'], this.songs[i]['name'],i);

        }
        this.reset_songs=this.songs;
        this.reset_songs['album']="ALL";


      });


    },

  data() {

    return{

      user: '',

      songs:  [],

      songname: '',

      name: '',

      reset_songs:'',

      some_toggle: false,

      previoussong: '',

      active:   false,

      image: 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/6c/No_image_3x4.svg/1280px-No_image_3x4.svg.png',

      index: 0,

      duration: '0:00',

      volume: 1000,

      songduration: '0',

      sliderValue:'0',

      interval: 1,

      audio: new Howl({ src: [ '' ] }),

      old:{active:''},

      disabled: false,

      sdisabled: false,

      ssingle: false,

      rand_song: [],

      rand_song_count: 0,

      count: 0,

      player: false,

      currenttime: '0:00',

      mins: 0,

      meta: [],

      album:[],


    }

  },

  methods: {


    resetAll()
    {
      this.some_toggle=!this.some_toggle;
      this.songs=this.reset_songs;
    },

    changed(){

      this.audio.seek(this.$refs.slider.value);

      this.sliderValue=parseInt(this.$refs.slider.value);

      if(Math.round(this.sliderValue)%60 ==0)
      {

        this.mins=Math.round(this.sliderValue)/60;

      }

       var secs = this.audio._duration %60;

      var mins = Math.round((this.audio._duration-secs)/60);

      secs=Math.round(secs);

      if(secs.toString().length==1)

        this.currenttime=this.mins+':0'+Math.round(this.sliderValue)%60;

      else

        this.currenttime=this.mins+':'+Math.round(this.sliderValue)%60;

    },

    async readFromBlob(blob,name,index) {

      var src='/songFile'+blob +'/'+ name;

      musicMetadata.fetchFromUrl(src).then(musicMetadata => {

      this.meta=musicMetadata;

      var image='data:image/png;base64,'+this.meta.common.picture[0].data.toString('base64');

      var id=this.songs[index]['id'];

      var b={
            song_id: id,
            name: name,
            user_id: this.songs[index]['user_id'],
            src: blob,
            image: image,
          }
      this.songs[index]=b;

      var count=0;

      var a={
        album: this.meta.common.album,
        song_id: [],
        user_id: [],
        name: [],
        image: image,
        location: []
        };

      if(this.album.length==0)
      {

          this.album.push(a);
          this.album[0].song_id.push(id);
          this.album[0].user_id.push(this.songs[index]['user_id']);
          this.album[0].name[0]=name;
          this.album[0].location.push(blob);
        }
        else{

          for(var i=0;i<this.album.length;i++)
          {
            if(this.album[i].album==this.meta.common.album)
            {
              this.album[i].song_id.push(id);
              this.album[i].user_id.push(this.songs[index]['user_id']);
              this.album[i].name.push(name);
              this.album[i].location.push(blob);
              count=1;
              break;
            }

          }

          if(count==0)
          {
            this.album.push(a);
            this.album[i].song_id.push(this.songs[index]['id']);
            this.album[i].user_id.push(this.songs[index]['user_id']);
            this.album[this.album.length-1].name.push(name);
            this.album[this.album.length-1].location.push(blob);
          }

        }


    });


  },



    volumechange() {

      this.audio.volume(this.$refs.volume.value/1000);

      this.volume=this.$refs.volume.value;

    },

    play(){

      var audio;

      if(this.player==false)
      {


        this.songname=this.songs[0]['name'];

        this.player=true;

        this.$children[this.index].isActive();

      }

      else{

        if(this.previoussong != this.name){



          this.songname= '/songFile'+this.songs[this.index]['src'] +'/'+ this.songname;

          this.image=this.songs[this.index]['image'];

          audio= new Howl({ src: this.songname });

          this.previoussong=this.name;

          this.audio.stop();

          this.audio=audio;

          this.sliderValue='0';

        }

        else
        {

          if(this.ssingle==true)

            this.audio.stop();

          else

            this.audio.pause();

        }

        if (!this.active) {

          this.audio.play();

          this.audio.volume(this.$refs.volume.value/1000);

          this.mins=0;

          clearInterval(this.interval);

          this.slider();

        }

        else{

          this.audio.pause();

          this.mins=0;

          clearInterval(this.interval);

          this.sliderValue=parseInt(this.$refs.slider.value);

        }

      this.active=!this.active;

      }

    },

    slider() {

      var k=setInterval(this.repeat, 1000);

      this.interval=k;

    },

    repeat()
    {

      var l=this.$refs.slider;

      l.max=this.audio.duration();

      var secs = this.audio._duration %60;

      var mins = Math.round((this.audio._duration-secs)/60);

      secs=Math.round(secs);

      if(secs.toString().length==1)

        this.duration = mins + ':0' + Math.round(secs);

      else

        this.duration = mins + ':' + Math.round(secs);

      if(l.value!=l.max)
      {

        this.sliderValue=this.audio.seek();

        if(Math.round(this.sliderValue)%60 ==0)
        {

          this.mins=Math.floor(this.sliderValue/60);

          //console.log(this.mins+"slider");

        }

        this.mins=Math.floor(this.sliderValue/60);

        //console.log(this.mins);

        var secs=Math.round(this.sliderValue)%60;

        if(secs.toString().length==1)

          this.currenttime=this.mins+':0'+Math.round(this.sliderValue)%60;

        else

          this.currenttime=this.mins+':'+Math.round(this.sliderValue)%60;

      }

      if(Math.round(this.audio.seek()) == Math.round(this.audio.duration())-1){

        this.mins=0;

        clearInterval(this.interval);

        if(this.ssingle==true)
        {

         this.index=this.index-1;

        }
        else if(this.disabled==true)
        {

          this.suffles();

        }

        this.playprevious();

      }

    },

    view(name) {

      this.active=true;

      this.songname=name;

      this.name=name;

      for(var i=0;i<this.songs.length;i++)
      {

        if(this.songs[i]['name']==name)

          this.index=i;

      }


      this.active=!this.active;

      this.play();

    },

    playprevious() {


      if(this.disabled==true)
      {

        this.suffles();

      }
      else{

        if(this.index+1 != this.songs.length)
        {

          this.index = this.index+1;

        }
        else {

          if(this.sdisabled==true)
          {
            this.index = 0;
          }

          else

          return;

        }

      }

      this.$children[this.index].isActive();

    },

    playnext() {

      if(this.disabled==true)
      {

        this.suffles();
      }

      else
      {

        if(this.index-1 != -1)
        {

          this.index = this.index-1;

        }

        else
        {

          if(this.sdisabled==true)

            this.index=this.songs.length-1;

          else

            return;
        }
      }

      this.$children[this.index].isActive();

    },

    suffle() {

      this.disabled=!this.disabled;

      this.ssingle=false;

    },

    suffles() {

      var count=1;

      while(count>0){

        var rand=Math.round(Math.random() * (this.songs.length-1));

        if(this.rand_song.indexOf(rand) == -1)
        {

          this.rand_song[this.rand_song_count++]=rand;

          this.index=rand;

          count=-1;

        }
        else if(this.songs.length==this.rand_song.length)
        {

          this.rand_song=[];

          this.rand_song_count=0;

          this.rand_song[this.rand_song_count++]=rand;

          this.index=rand;

        }

      }

    },

    repeats() {

      this.sdisabled=!this.sdisabled;

      this.ssingle=false;

    },

    single() {

      this.ssingle=!this.ssingle;

      this.sdisabled=false;

      this.disabled=false;
    }

  }

}

</script>

<style>

.name{
  height:100%;
  width: 100%;
}

.audio{

  background-image: linear-gradient(to top, #00c6fb 0%, #005bea 100%);
  padding: 50px;
  border : hidden;
  border-radius: 25px;

}

.music{

  position : fixed;
  overflow-y: scroll;
}
.play{

    background-repeat: no-repeat;
    background-position: 50% 50%;
    height: 25px;
    width: 45px;
    border: none;
    display: inline-block;
    border: hidden;
    margin-top: 2%;
}

.play.active{

  background-image: url(https://image.flaticon.com/icons/svg/149/149657.svg);

}

.play.pause{

  background-image: url(https://image.flaticon.com/icons/svg/151/151859.svg);

}

.play.previous{

  background-image: url('https://image.flaticon.com/icons/svg/12/12067.svg');

}

.play.next{

  background-image: url('https://image.flaticon.com/icons/svg/12/12314.svg ');

}

.play.suffle.suffles{

  background-image: url('https://image.flaticon.com/icons/svg/149/149674.svg')

}

.play.suffle{

  background-image: url('https://image.flaticon.com/icons/svg/149/149131.svg');

}

.play.repeat{

  background-image: url('https://image.flaticon.com/icons/svg/149/149133.svg');

}


.play.repeat.repeats{

  background-image: url('https://image.flaticon.com/icons/svg/149/149676.svg');
}

.single{

  background-repeat: no-repeat;
  background-position: 50% 50%;
  height: 40px;
  width: 45px;
  border: none;
  display: inline-block;
  padding-top: 2%;
  padding-bottom: 1%;
  background-image: url('noun_Repeat.svg');

}

.singles{

  background-image: url('noun_Repeat One_2195448.svg');
}



h2{
  font-size: 0.2rem;
  margin-top: 5%;
}

.image{

  width: 250px;
  height: 225px;

}



.slidecontainer {

  width: 100%;

}

.slider {

  -webkit-appearance: none;
  width: 75%;
  height: 15px;
  border-radius: 5px;
  margin-top: 2%;
  background-color: #2d3436;
  background-image: linear-gradient(315deg, #2d3436 0%, #000000 74%);
  outline: none;
  opacity: 0.7;
  -webkit-transition: .2s;
  transition: opacity .2s;
}

.slider:hover {

  opacity: 1;

}

.slider::-webkit-slider-thumb {

  -webkit-appearance: none;
  appearance: none;
  width: 25px;
  height: 25px;
  border-radius: 50%;
  background: #00C9FF;
  background: -webkit-linear-gradient(to right, #92FE9D, #00C9FF);
  background: linear-gradient(to right, #92FE9D, #00C9FF);
  cursor: pointer;

}


.slider::-moz-range-thumb {

  width: 25px;
  height: 25px;
  border-radius: 25%;
  background: #00C9FF;
  background: -webkit-linear-gradient(to right, #92FE9D, #00C9FF);
  background: linear-gradient(to right, #92FE9D, #00C9FF);
  cursor: pointer;

}

.custom-range{
  color: ligthblack;
}

.custom-range[type=range]{

  width: 9%;
}
.custom-range[type=range]::-webkit-slider-thumb{

  background: black;
}

</style>
