const childA = {
    namespaced: true,
    state: {
        score: 0
    },
    getters: {
        score (state) {
            return state.score
        }
    },
    mutations: {
        increment (state, step) {
            state.score += step
        }
    },
    actions: {
        incrementScore: ({ commit }, delay) => {
            setTimeout(() => {
                commit('increment', 5)
            }, delay)
        }
    }
};

export default childA;