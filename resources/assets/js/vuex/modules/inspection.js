const state = {
  isUploading: false,
  inspectionId: '',
  editInspectionId: ''
}

const mutations = {
  'SET_INSPECTION_IMAGES' (state, status) {
    state.isUploading = status
  },
  'SET_INSPECTION_ID' (state, id) {
    state.inspectionId = id
  },
  'SET_EDIT_INSPECTION_ID' (state, id) {
    state.editInspectionId = id
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
  },
  isEditInspectionId: (state) => {
    return state.editInspectionId
  }
}

export default {
  state,
  mutations,
  actions,
  getters
}
