const childB = {
    namespaced: true,
    state: {
        score: 3
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
                commit('increment', 1)
            }, delay)
        }
    }
};

export default childB;