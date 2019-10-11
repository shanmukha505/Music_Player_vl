<template>


	<div class="container">

    <play></play>

  </div>	

     
</template>


<script>

import Play from './Play.vue';

export default {

  components: {


    Play

  },

  mounted()
    {
      axios.post('/song').then(response => {

        this.song=response.data;

        console.log(this.song[1]['name']);

      });

      axios.post('/songsrc').then(response =>{

        this.songname=response.data;

        console.log(this.songname);

      });

      
    },


  data() {

    

    return {

      song: [],

      songname: '',
      

		  songs: [


      'Shawn Mendes_Camila Cabello_Senorita.mp3', 

      'Keep.mp3',
      
      'TunePocket-Short-Subtle-Hi-Tech-Logo-Preview.mp3',

      'isthere.mp3',

      '/shanmukha_1/01 - Ismart Title Song.mp3',

      '02 - Dimaak Kharaab.mp3' ,

      '03 - Zindabad Zindabad.mp3', 

      '05 - Undipo.mp3',

      'isthere.mp3',


      ],

      songimages: [

        'https://images.unsplash.com/photo-1511379938547-c1f69419868d?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',

        'https://images.unsplash.com/photo-1459749411175-04bf5292ceea?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',

        'https://images.unsplash.com/photo-1453738773917-9c3eff1db985?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',

        'https://images.unsplash.com/photo-1421217336522-861978fdf33a?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60',

        'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ,

        'https://images.unsplash.com/photo-1433622070098-754fdf81c929?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ,

        'https://images.unsplash.com/photo-1421757350652-9f65a35effc7?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ,

        'https://images.unsplash.com/photo-1488036106564-87ecb155bb15?ixlib=rb-1.2.1&auto=format&fit=crop&w=500&q=60' ,

      ]
    
    }
  
  },

}

</script>

<style>

span{

  margin-left: auto;
}

</style>