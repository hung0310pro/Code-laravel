import Vue from "vue";
import Vuex from "vuex";

Vue.use(Vuex);

import childA from "./store/modules/childA";
import childB from "./store/modules/childB";

export const store = new Vuex.Store({
    modules: {
        scoreBoard: childA,
        resultBoard: childB
    }
});

export default store;