
<template>
  
  <div class="container">
  
    <div class="container" v-if="user!=''">
  
        <div class="large-12 medium-12 small-12 filezone">
  
            <input type="file" class="files" id="files" ref="files" multiple v-on:change="handleFiles()"/>
  
            <p>
  
                Drop your files here <br>or click to search
  
            </p>
  
        </div>

  
        <div v-for="(file, key) in files" class="file-listing">
  
            <img class="preview" v-bind:ref="'preview'+parseInt(key)"/>
  
            {{ file.name }}


            <select v-model="visiblitiy[key]">

                <option>public</option>

                <option>private</option>

            </select>

            <div class="success-container" v-if="file.id > 0">

                Success

            </div>

            <div class="remove-container" v-else>

                <a class="remove" v-on:click="removeFile(key)">Remove</a>

            </div>

        </div>

        <a class="submit-button" v-on:click="submitFiles()" v-show="files.length > 0">Submit</a>

        <div>

          your uploaded songs

          <div v-for="(song, key) in songs" class="">
              

          </div>

        </div>

    </div>

    <h1 class="title is-1" v-else>Please log in</h1>

  </div>

</template>

<script>

  export default {

    data() {

       return {

        files: [],

        user:'',

        visiblitiy: [],

      }

    },


    mounted()
    {

      axios.post('/isLoggedIn').then(response => {

        this.user=response.data;

      });


    },




    methods: {

      handleFiles() {
    
        let uploadedFiles = this.$refs.files.files;

        for(var i = 0; i < uploadedFiles.length; i++) {
        
          this.files.push(uploadedFiles[i]);

          this.visiblitiy[i]="public";
        
        }
        
        
      },

      removeFile( key ){
          
          this.files.splice( key, 1 );
      
      },
      
      submitFiles() {
        
        for( let i = 0; i < this.files.length; i++ ){
        
        if(this.files[i].id) {
        
            continue;
        
        }
        
        let formData = new FormData();
        
        formData.append('file', this.files[i]);
        
        formData.append('name',this.files[i].name);

        formData.append('visible',this.visiblitiy[i]);

        axios.post('/upload',
        
            formData,
            {
        
                headers: {
        
                    'Content-Type': 'multipart/form-data'
        
                }
        
            }
        
          ).then(function(data) {
        
            this.files[i].id = data['data']['id'];
        
            this.files.splice(i, 1, this.files[i]);
        
            console.log('success');
        
          }.bind(this)).catch(function(data) {
        
            console.log('error');
        
          });
      
      }
      
    }
  
  }
        
}
</script>

<style scoped>
    input[type="file"]{
        opacity: 0;
        width: 100%;
        height: 200px;
        position: absolute;
        cursor: pointer;
    }
    .filezone {
        outline: 2px dashed grey;
        outline-offset: -10px;
        background: #ccc;
        color: dimgray;
        padding: 10px 10px;
        min-height: 200px;
        position: relative;
        cursor: pointer;
    }
    .filezone:hover {
        background: #c0c0c0;
    }

    .filezone p {
        font-size: 1.2em;
        text-align: center;
        padding: 50px 50px 50px 50px;
    }
    div.file-listing img{
        max-width: 90%;
    }

    div.file-listing{
        margin: auto;
        padding: 10px;
        border-bottom: 1px solid #ddd;
    }

    div.file-listing img{
        height: 100px;
    }
    div.success-container{
        text-align: center;
        color: green;
    }

    div.remove-container{
        text-align: center;
    }

    div.remove-container a{
        color: red;
        cursor: pointer;
    }

    a.submit-button{
        display: block;
        margin: auto;
        text-align: center;
        width: 200px;
        padding: 10px;
        text-transform: uppercase;
        background-color: #CCC;
        color: white;
        font-weight: bold;
        margin-top: 20px;
    }
</style>