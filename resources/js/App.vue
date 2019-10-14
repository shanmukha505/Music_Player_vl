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

  data() {

    return {

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