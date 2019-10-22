<template>

	<div>

		<div style="display:flex;">

			<div class="songnames" id="name" :class="{'active':active}" v-on:click="isActive">

	    		<a class="btn btn-outline-success" style="background-color: lightyellow;width:100%;" >{{ name }}</a>

			</div>

		<div class="list dropdown" >

			<img class="btn" src="https://image.flaticon.com/icons/svg/54/54878.svg" data-toggle="dropdown" />

			<div class="dropdown-menu" >

				<a class="dropdown-item" >add to playlist</a>

				<a class="dropdown-item" v-on:click="view=!view">create playlsit</a>

			</div>

		</div>

	</div>
	
	<div v-if="!view">
		
		<input type="text" v-model="title" placeholder="enter playlist name">
		
		<button type="submit" class="btn btn-primary" v-on:click="create()">submit</button>
	
	</div>

</div>

</template>

<style>
	
	#name{

		margin-top: 1%;

	}

</style>

<script>


export default {

	props:["song", "src","user_id" ,"song_id"],

	data(){

		return{

			name:this.song,

			songname: '',
			
			active:false,

			view: true,

			playlist_create: [],

			title: ''

		}
	},

	methods: {
	
		isActive(value) {

			this.$parent.$data.old.active=false;
			
			this.$emit('selected',this.name);
			
			this.$parent.$data.old=this;

			this.$parent.$data.name=this.name;

			this.active=true;

		},

		create()
		{
			let formData = new FormData();
			//console.log("hello");

			formData.append('name',this.title);
			formData.append('song_id',this.song_id);
			formData.append('user_id',this.user_id);
			formData.append('song',this.song);
			formData.append('song_src',this.src);
			this.view=!this.view;
			axios.post('/play',
        
            formData,
            {
        
                headers: {
        
                    'Content-Type': 'multipart/form-data'
        
                }
        
            }
        
          )
		}

		
		
	}
	
}

</script>


<style>

.btn{

	font-size: 1rem;

	max-width: 100%;

	max-height: 100%;

}
img{
	background-repeat: no-repeat;
    background-position: 50% 50%;  
    height: 30px;
    width: 50px;
    border: none;
    border: hidden;
    margin-top: 4%;
}

.songnames{
	
	display: inline-block;
	width: 95%;

}

.list{
	margin-top: 1%;
}

.active{

	border: 3px solid;
  	border-image: conic-gradient(red, yellow, lime, aqua, blue, magenta, red) 1;

}
</style>