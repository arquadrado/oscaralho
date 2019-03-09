const categories = handover && handover.categories ? handover.categories : [];

const state = {
  categories: categories,
  selectedCategoryToEdit: undefined,
};
const getters = {
  getCategoriesToEdit: state => state.categories,
  getSelectedCategoryToEdit: state => state.selectedCategoryToEdit,
};
const actions = {
  setCategoryToEdit: ({ commit }, category) => {
    commit('SET_CATEGORY_TO_EDIT', category)
  },
  saveCategory: ({ commit }, data) => {
    axios.post('/category', data)
      .then((response) => {
        commit('ADD_CATEGORY', response.data.category);
      })
      .catch(function (error) {
        console.log(error);
      });
  },
  deleteCategory: ({ commit }, data) => {
    commit('REMOVE_CATEGORY', data);
    axios.delete('/category', { data: data })
      .then((response) => {
      })
      .catch(function (error) {
        commit('ADD_CATEGORY', data);
        console.log(error);
      });
  }
};
const mutations = {
  'SET_CATEGORY_TO_EDIT': (state, category) => {
    state.selectedCategoryToEdit = category
  },
  'ADD_CATEGORY': (state, data) => {
    let category = state.categories.find(c => c.id === data.id);
    if (category) {
      category = { ...category, ...data }
    } else {
      state.categories.push(data);
    }
  },
  'REMOVE_CATEGORY': (state, data) => {
    let category = state.categories.find(c => c.id === data.id);
    const index = state.categories.indexOf(category);
    if (index > -1) {
      state.categories.splice(index, 1);
    }
  }
};

export default {
  state,
  getters,
  actions,
  mutations
}