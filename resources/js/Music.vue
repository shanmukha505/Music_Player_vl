<template>
		
	<section class="songnames" id="name" :class="{'active':active}" v-on:click="isActive">

	    <a class="button" style="background-color: lightyellow;width:100%;" >{{ name }}</a>

	</section>

</template>

<style>
	
	#name{

		margin-top: 1%;

	}

</style>

<script>


export default {

	props:["song", "src"],

	data(){

		return{

			name:this.song,

			songname: '',
			
			active:false,

		}
	},

	methods: {
	
		isActive(value) {

			this.$parent.$data.old.active=false;
			
			this.$emit('selected',this.name);
			
			this.$parent.$data.old=this;

			this.$parent.$data.name=this.name;

			this.active=true;

		}
		
	}
	
}

</script>


<style>

.button{

	font-size: 1rem;

	max-width: 100%;

	max-height: 100%;

}

.songnames{

	text-align: justify;
	text-justify: inter-word;
}

.active{

	border: 3px solid;
  	border-image: conic-gradient(red, yellow, lime, aqua, blue, magenta, red) 1;

}
</style>