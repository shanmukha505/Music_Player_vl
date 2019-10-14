<template>

<div class="name" id="play" >

    <div class="audio" align="center" >

      <img class="image is-4by2" :src="image" align="center" style="margin-bottom: 2%;"/>

      <h2 style="font-size: 1rem; ">{{ this.name }} </h2>

      <div class="is-center">

        <button class="play previous" type="button"  v-on:click="playnext" title="previous"></button>

        <button class="play" v-on:click="play" title="play" :class="{'active':!active,'pause':active}"  type="button" ></button>
         
        <button class="play next" type="button" title="next" v-on:click="playprevious"></button>

        <div style="display:inline;padding-left:4%;">
     
          <input class="volume" step="1" min="0" max="1000" :value="volume" type="range" @change="volumechange" ref="volume" orient="vertical" style="">

        </div>
       
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

    </div>

    <div v-for="song in songs">
         
      <music  v-bind:song="song.name" v-bind:src="song.src" @selected="view"></music>
       
    </div>

  </div>

</template>

<script>

import Music from './Music.vue';

import {Howl, Howler} from 'howler';

import './bootstrap';


export default {

  components:{
   
    Music,        
             
  },

  mounted()
    {
      

      axios.post('/isLoggedIn').then(response => {

        this.user=response.data;
        

      });

      axios.post('/song').then(response => {


        this.songs=response.data;
        
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


      });
      


      
    },

  data() {
   
    return{

      user: '',

      songs:  [],

      images: this.$parent.$data.songimages,

      songname: '',

      name: '',

      previoussong: 'Shawn Mendes_Camila Cabello_Senorita.mp3',

      active:   false,

      image: 'data:image/png;base64,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',

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

    }
   
  },

  methods: {

    changed(){

     //console.log(this.$refs.slider.value);

      this.audio.seek(this.$refs.slider.value);

      this.sliderValue=parseInt(this.$refs.slider.value);

      if(Math.round(this.sliderValue)%60 ==0)
          {

            this.mins=Math.round(this.sliderValue)/60;

          }

          this.mins=Math.round(Math.round(this.sliderValue)/60);
          
          this.currenttime=this.mins+':'+Math.round(this.sliderValue)%60;

   
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

      var mins = Math.floor(this.audio._duration/60);

      var secs = this.audio._duration - mins * 60;

      this.duration = mins + ':' + Math.floor(secs);

      if(l.value!=l.max){

          this.sliderValue=this.audio.seek();

          if(Math.round(this.sliderValue)%60 ==0)
          {

            this.mins=Math.round(this.sliderValue)/60;

          }

          this.mins=Math.round(Math.round(this.sliderValue)/60);
          
          this.currenttime=this.mins+':'+Math.round(this.sliderValue)%60;

        }

        if(Math.round(this.audio.seek()) == Math.round(this.audio.duration())-1){

          this.mins=0;

          clearInterval(this.interval);

          if(this.ssingle==true)
          {

            //console.log(this.index);

            this.index=this.index-1;

          }
          else if(this.disabled==true)
          {

            this.suffles();

          }

          this.playprevious();

        }

    }
    ,

    view(name) {

      this.active=true;

      this.songname=name;
     
      this.name=name;

      for(var i=0;i<this.songs.length;i++)
      {
        if(this.songs[i]['name']==name)
          this.index=i;
      }
     
      this.image=this.images[this.index];

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

      //console.log(this.songs.length);

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

              console.log(rand);

              this.rand_song=[];

              this.rand_song_count=0;

              this.rand_song[this.rand_song_count++]=rand;

              this.index=rand;

              //console.log('songsrand length'+this.rand_song.length);

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

.play{

    background-repeat: no-repeat;
    background-position: 50% 50%;  
    height: 25px;
    width: 45px;
    border: none;
    display: inline-block;

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

.audio{

  background: linear-gradient(90deg, rgb(290,224,66),rgb(0,143,144));
  padding: 50px;
  border : hidden;
  border-radius: 25px;

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

.volume {
  -webkit-appearance: none;
  -moz-appearance: none;
  position: absolute;
  left: auto;
  border-radius:45px;
  margin-top:2.7%;
  width: 7%;
  height: 1.5%;
  transform: translate(-40%, -40%);
}


volume[type=range] {
  -webkit-appearance: none;
  background: transparent;
}

.volume[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;
}

.volume[type=range]:focus {
  outline: none; }


.volume[type=range]::-webkit-slider-thumb {
  -webkit-appearance: none;

  height: 200%;
  width: 23%;
  border-radius: 50%;
  border: 2px solid #fff;
  background: #000000;
  cursor: pointer;
  margin-top: -6px;
  box-shadow: inset 0px 1px 3px rgba(0,0,0,0.9);}

.volume[type=range]::-webkit-slider-runnable-track {
  width: 100%;
  height: 100%;
  cursor: pointer;
  background: #000000;
  border-radius: 6px;
  box-shadow: inset 0px 1px 3px rgba(0,0,0,0.9);
}


.volume[type=range]::-moz-range-thumb {
  -moz-appearance: none;
  border: 2px solid;
  border-radius: 50%;
  height: 25px;
  width: 25px;
  max-width: 80px;
  position: relative;
  bottom: 11px;
  background-color: #1d1c25;
  cursor: -moz-grab;
  -moz-transition: border 1000ms ease;
  transition: border 1000ms ease;

}

.range.blue::-moz-range-thumb {
   border-color: rgb(59,173,227);
}

.range.ltpurple::-moz-range-thumb {
   border-color: rgb(87,111,230);
}

.range.purple::-moz-range-thumb {
   border-color: rgb(152,68,183);
}

.range.pink::-moz-range-thumb {
   border-color: rgb(255,53,127);
}

.volume[type=range]::-moz-range-thumb:active {
  cursor: -moz-grabbing;
}


.volume[type=range]::-moz-range-track {
  -moz-appearance: none;
  background: rgba(59,173,227,1);
  background: -moz-linear-gradient(45deg, rgba(59,173,227,1) 0%, rgba(87,111,230,1) 25%, rgba(152,68,183,1) 51%, rgba(255,53,127,1) 100%);
  background: -webkit-gradient(left bottom, right top, color-stop(0%, rgba(59,173,227,1)), color-stop(25%, rgba(87,111,230,1)), color-stop(51%, rgba(152,68,183,1)), color-stop(100%, rgba(255,53,127,1)));
  background: -webkit-linear-gradient(45deg, rgba(59,173,227,1) 0%, rgba(87,111,230,1) 25%, rgba(152,68,183,1) 51%, rgba(255,53,127,1) 100%);
  background: -o-linear-gradient(45deg, rgba(59,173,227,1) 0%, rgba(87,111,230,1) 25%, rgba(152,68,183,1) 51%, rgba(255,53,127,1) 100%);
  background: -ms-linear-gradient(45deg, rgba(59,173,227,1) 0%, rgba(87,111,230,1) 25%, rgba(152,68,183,1) 51%, rgba(255,53,127,1) 100%);
  background: linear-gradient(45deg, rgba(59,173,227,1) 0%, rgba(87,111,230,1) 25%, rgba(152,68,183,1) 51%, rgba(255,53,127,1) 100%);
  filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#3bade3 ', endColorstr='#ff357f ', GradientType=1 );
  height: 2px;
}

 



</style>