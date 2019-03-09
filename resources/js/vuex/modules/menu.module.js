const categories = handover && handover.categories ? handover.categories : [];
const budgets = handover && handover.budgets ? handover.budgets : [];

const state = {
  budgets: budgets,
  categories: categories,
  selectedCategoryToEdit: undefined,
  selectedBudgetToEdit: undefined,
};
const getters = {
  getBudgetsToEdit: state => state.budgets.sort((a, b) => {
    return `${a.year}-${a.month}` < `${b.year}-${b.month}`
  }),
  getCategoriesToEdit: state => state.categories,
  getSelectedCategoryToEdit: state => state.selectedCategoryToEdit,
  getSelectedBudgetToEdit: state => state.selectedBudgetToEdit,
};
const actions = {
  setCategoryToEdit: ({ commit }, category) => {
    commit('SET_CATEGORY_TO_EDIT', category)
  },
  setBudgetToEdit: ({ commit }, budget) => {
    commit('SET_BUDGET_TO_EDIT', budget)
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
  },
  saveBudget: ({ commit, dispatch }, data) => {
    axios.post('/budget', data)
      .then((response) => {
        commit('ADD_BUDGET', response.data.budget);
        dispatch('addBounds', response.data.bounds);
      })
      .catch(function (error) {
        console.log(error);
      });
  },
  deleteBudget: ({ commit, dispatch }, data) => {
    commit('REMOVE_BUDGET', data);
    axios.delete('/budget', { data: data })
      .then((response) => {
        dispatch('deleteBounds', data.id);
      })
      .catch(function (error) {
        commit('ADD_BUDGET', data);
        console.log(error);
      });
  }
};
const mutations = {
  'SET_CATEGORY_TO_EDIT': (state, category) => {
    state.selectedCategoryToEdit = category
  },
  'SET_BUDGET_TO_EDIT': (state, budget) => {
    state.selectedBudgetToEdit = budget
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
  },
  'ADD_BUDGET': (state, data) => {
    let budget = state.budgets.find(c => c.id === data.id);
    if (budget) {
      budget = { ...budget, ...data }
    } else {
      state.budgets.push(data);
    }
  },
  'REMOVE_BUDGET': (state, data) => {
    let budget = state.budgets.find(c => c.id === data.id);
    const index = state.budgets.indexOf(budget);
    if (index > -1) {
      state.budgets.splice(index, 1);
    }
  }
};

export default {
  state,
  getters,
  actions,
  mutations
}