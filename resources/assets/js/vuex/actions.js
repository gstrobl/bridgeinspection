// // import Vue from 'vue';
// import unsplash from '../config/unsplash-config'
// import { firebaseAuth, db, dbStorage } from '../config/firebase-config'
// import { toJson } from 'unsplash-js'
//
export function setInspectionsImages ({commit}, status) {
  commit('SET_INSPECTION_IMAGES', status)
}

export function setInspectionId ({commit}, id) {
  commit('SET_INSPECTION_ID', id)
}
//
// export function loginWithEmail ({commit}, {email, password}) {
//   return firebaseAuth().signInWithEmailAndPassword(email, password)
// }
//
// export const logout = ({commit}) => {
//   return firebaseAuth().signOut()
// }
//
// export function addPortfolioItem ({commit}, {portfolioObject, featureImage}) {
//   let portfolioRef = db.ref('portfolio')
//   let newPortfolioRef = portfolioRef.push()
//   portfolioObject.id = newPortfolioRef.key
//   let storageRef = dbStorage.ref('portfolioImages/' + newPortfolioRef.key + '.jpg')
//   portfolioObject.createdAt = new Date().toUTCString()
//
//   let uploadTask = storageRef.put(featureImage)
//   uploadTask.on('state_changed', function (snapshot) {
//     let progress = (snapshot.bytesTransferred / snapshot.totalBytes) * 100
//     console.log('Upload is ' + progress + '% done')
//   }, function (error) {
//     console.log('ERROR' + error)
//   }, function () {
//     portfolioObject.imgURL = uploadTask.snapshot.downloadURL
//     portfolioObject.path = storageRef.fullPath
//     newPortfolioRef.set(portfolioObject)
//   })
// }
//
// export function getPortfolioItem ({commit}, {itemId}) {
//   let itemObj = {}
//   db.ref('portfolio/' + itemId).on('value', (item) => {
//     itemObj = item.val()
//   })
//   return itemObj
// }
//
// export function updatePortfolioItem ({commit}, {portfolioObject, itemId}) {
//   let portfolioRef = db.ref('portfolio/' + itemId)
//   portfolioObject.editedAt = new Date().toUTCString()
//   portfolioRef.set(portfolioObject)
//   commit('LAST_EDIT_ITEM', portfolioObject)
// }
//
// export function deletePortfolioItem ({commit}, {itemId}) {
//   db.ref('portfolio/' + itemId).remove()
//   let removeRef = dbStorage.ref('portfolioImages/' + itemId + '.jpg')
//   removeRef.delete().then(function () {
//     console.log('successfully deleted')
//   }).catch(function (error) {
//     console.log(error)
//   })
// }
//
// export function getPortfolioItems ({commit}) {
//   return db.ref('portfolio').on('value', (items) => {
//     commit('UPDATE_PORTOLIO_ITEMS', items.val())
//   })
// }
