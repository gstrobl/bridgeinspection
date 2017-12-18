const state = {
  isLoading: true,
  portfolioItems: [],
  lastEditItem: []
}

const mutations = {
  'UPDATE_PORTOLIO_ITEMS' (state, portfolioItems) {
    state.portfolioItems = portfolioItems
    state.isLoading = false
  },
  'LAST_EDIT_ITEM' (state, lastEditItem) {
    state.lastEditItem = lastEditItem
  }
}

const actions = {

}

const getters = {
  portfolioItems: (state) => {
    return state.portfolioItems
  },
  lastEditItem: (state) => {
    return state.lastEditItem
  },
  isPortfolioLoading: (state) => {
    return state.isLoading
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}
