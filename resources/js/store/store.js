import Vue from "vue";
import Vuex from "vuex";

import childA from "./modules/childA.js";
import childB from "./modules/childB.js";

Vue.use(Vuex);

export const store = new Vuex.Store({
    modules: {
        scoreBoard: childA,
        resultBoard: childB
    }
});

export default store;