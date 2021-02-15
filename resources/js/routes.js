import VueRouter from "vue-router";
import "./bootstrap";

let routes = [
    {
        path: "/",
        component: require("./App.vue").default
    },

    {
        path: "/upload",
        component: require("./upload.vue").default
    }
];

export default new VueRouter({
    routes
});
