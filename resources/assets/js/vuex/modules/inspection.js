const state = {
  isUploading: false,
  inspectionId: ''
}

const mutations = {
  'SET_INSPECTION_IMAGES' (state, status) {
    state.isUploading = status
  },
  'SET_INSPECTION_ID' (state, id) {
    state.inspectionId = id
  }
}

const actions = {
}

const getters = {
  isImageUploading: (state) => {
    return state.isUploading
  },
  isInspectionId: (state) => {
    return state.inspectionId
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}
