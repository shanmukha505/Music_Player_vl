<template>
    <div>
        <div class="dropdown" @click="selected">
            <button
                type="button"
                class="btn btn-primary dropdown-toggle"
                style="width:100%;"
            >
                {{ album.album }}
            </button>
        </div>
    </div>
</template>

<script>
import music from "./Music.vue";

export default {
    components: {
        music
    },

    props: ["album"],

    data() {
        return {
            songs: [],
            user: ""
        };
    },
    methods: {
        selected() {
            if (this.album.album == "ALL") {
                this.$emit("resetIt");
                return;
            }

            for (var i = 0; i < this.album.name.length; i++) {
                this.songs[i] = {
                    song_id: this.album.song_id[i],
                    user_id: this.album.user_id[i],
                    name: this.album.name[i],
                    src: this.album.location[i],
                    image: this.album.image
                };
            }

            this.$parent.$data.songs = this.songs;
            this.$parent.$data.some_toggle = !this.$parent.$data.some_toggle;
        }
    }
};
</script>
