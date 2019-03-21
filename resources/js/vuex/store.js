import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import MenuModule from './modules/menu.module';
import UtilsModule from './modules/utils.module';

const user = handover && handover.user ? handover.user : [];
const bounds = handover && handover.bounds ? handover.bounds : [];

const state = {
  currentView: 'grid-month',
  currentCategoryType: 'expense',
  categoryViewMode: 'icon',
  user: user,
  bounds: bounds,
  selectedBound: undefined,
  showDisplayPanel: false,
  selectedYear: `${new Date(Date.now()).getFullYear()}`,
  selectedMonth: `0${(new Date(Date.now()).getMonth() + 1)}`,
  justUpdated: undefined,
};
const getters = {
  getUser: state => state.user,
  getCategoryViewMode: state => state.categoryViewMode,
  getBounds: state => state.bounds,
  getBoundsByMonth: state => state.bounds.filter((bound) => {
    if (state.currentCategoryType === 'expense') {
      return bound.year === state.selectedYear &&
        bound.month === state.selectedMonth &&
        bound.category.expense;
    }
    return bound.year === state.selectedYear &&
      bound.month === state.selectedMonth &&
      !bound.category.expense;
  }),
  getBoundsByYear: state => state.bounds.filter((bound) => {
    return bound.year === state.selectedYear;
  }),
  getSelectedBoundId: state => state.selectedBound,
  getSelectedBound: state => state.bounds.find(c => c.id === state.selectedBound),
  shouldDisplayPanel: state => state.showDisplayPanel,
  getSelectedYear: state => state.selectedYear,
  getCurrentYearMonths: (state) => {
    return state.bounds.reduce((reduced, bound) => {
      if (
        state.selectedYear &&
        bound.year === state.selectedYear.toString() &&
        reduced.indexOf(bound.month) === -1
      ) {
        reduced.push(bound.month);
      }
      return reduced;

    }, []).sort();
  },
  getAllTimeYears: (state) => {
    return state.bounds.reduce((reduced, bound) => {
      if (reduced.indexOf(bound.year) === -1) {
        reduced.push(bound.year);
      }
      return reduced;

    }, []).sort();
  },
  getSelectedMonth: state => state.selectedMonth,
  getCurrentView: state => state.currentView,
  getCurrentCategoryType: state => state.currentCategoryType,
  isJustUpdated: state => state.justUpdated,
};
const actions = {
  setCategoryViewMode: ({ commit }, mode) => {
    commit('SET_CATEGORY_VIEW_MODE', mode);
  },
  selectBound: ({ commit, state }, categoryId) => {
    commit('SELECT_BOUND', categoryId);
  },
  setJustUpdated: ({ commit }, identifier) => {
    commit('SET_JUST_UPDATED', identifier);
  },
  setShowDisplayPanel: ({ commit }, value) => {
    commit('SET_SHOW_DISPLAY_PANEL', value);
  },
  addExpense: ({ commit }, expenseData) => {
    axios.post('/expense', expenseData)
      .then((response) => {
        commit('ADD_EXPENSE', response.data.expense);
      })
      .catch(function (error) {
        console.log(error);
      });
  },
  removeExpense: ({ commit }, expense) => {
    commit('REMOVE_EXPENSE', expense);

    axios.delete('/expense', { data: expense })
      .then()
      .catch(() => {
        commit('ADD_EXPENSE', expense);
      })
  },
  updateBounds: ({ commit, state }, bounds) => {

    if (bounds.length) {
      const budgetId = bounds[0].budget_id;

      const budgetBounds = state.bounds.filter(b => b.budget_id === budgetId);

      const currentBoundsIds = bounds.map(b => b.id);

      const indexesToRemove = [];

      budgetBounds.forEach((bound, i) => {
        let index = currentBoundsIds.indexOf(bound.id);

        if (index === -1) {
          indexesToRemove.push(i);
        }
      });

      commit('DELETE_BOUNDS', indexesToRemove);

      const boundsToAdd = bounds.filter(bound => {
        let boundToAdd = state.bounds.find(b => b.id === bound.id);
        return !boundToAdd;
      });

      commit('ADD_BOUNDS', boundsToAdd);

    }
  },
  deleteBounds: ({ commit }, budgetId) => {
    const indexesToRemove = state.bounds.map((b, i) => {
      if (b.budget_id === budgetId) {
        return i;
      }
      return false;
    })
      .filter(i => i);

    commit('DELETE_BOUNDS', indexesToRemove);
  },
  updateCategoryBound: ({ commit }, data) => {
    const bound = state.bounds.find(c => c.id === data.boundId);

    if (bound) {

      const boundPreviousValue = bound.bound_in_cents;
      commit('UPDATE_BOUND', { ...data, boundId: bound.id });
      axios.post('/update-bound', { ...data, boundId: bound.id })
        .then(function (response) {
          if (response && response.data) {
          }
        })
        .catch(function (error) {
          console.log(error);
          commit('UPDATE_BOUND', { ...data, value: boundPreviousValue });
        });
    }
  },

  setMonth: ({ commit, dispatch }, month) => {
    dispatch('setShowDisplayPanel', false);
    commit('SET_MONTH', month);
  },
  setYear: ({ commit, dispatch }, year) => {
    dispatch('setShowDisplayPanel', false);
    commit('SET_YEAR', year);
  },
  setCurrentView: ({ commit, dispatch }, view) => {
    dispatch('setShowDisplayPanel', false);
    commit('SET_CURRENT_VIEW', view);
  },
  setCurrentCategoryType: ({ commit, dispatch }, type) => {
    dispatch('setShowDisplayPanel', false);
    commit('SET_CURRENT_CATEGORY_TYPE', type);
  },

};
const mutations = {
  'SET_CATEGORY_VIEW_MODE': (state, mode) => {
    state.categoryViewMode = mode;
  },
  'SELECT_BOUND': (state, categoryId) => {
    state.selectedBound = categoryId;
  },
  'SET_JUST_UPDATED': (state, identifier) => {
    state.justUpdated = identifier;
  },
  'SET_SHOW_DISPLAY_PANEL': (state, value) => {
    state.showDisplayPanel = value;
  },
  'ADD_EXPENSE': (state, data) => {
    const bound = state.bounds.find(c => c.id === data.bound_id);
    if (bound) {
      const expenseExists = bound.expenses.find(e => e.id === data.id);
      if (expenseExists) {
        Object.keys(data).forEach((key) => {
          Vue.set(expenseExists, key, data[key]);
        });
      } else {
        bound.expenses.push(data);
      }

    }
  },
  'REMOVE_EXPENSE': (state, exp) => {
    const bound = state.bounds.find(c => c.id === exp.bound_id);
    if (bound && bound.expenses) {
      const expense = bound.expenses.find(e => e.id === exp.id);

      if (expense) {
        bound.expenses.splice(bound.expenses.indexOf(expense), 1)
      }
    }
  },
  'UPDATE_BOUND': (state, data) => {
    const bound = state.bounds.find(c => c.id === data.boundId);

    if (bound) {
      bound.bound_in_cents = data.value;
    }
  },
  'ADD_BOUNDS': (state, bounds) => {
    bounds.forEach(bound => {
      state.bounds.push(bound);
    });
  },
  'DELETE_BOUNDS': (state, indexesToRemove) => {
    for (var i = indexesToRemove.length - 1; i >= 0; i--) {
      state.bounds.splice(indexesToRemove[i], 1);
    }
  },
  'SET_MONTH': (state, month) => {
    state.selectedMonth = month;
  },
  'SET_YEAR': (state, year) => {
    state.selectedYear = year;
  },
  'SET_CURRENT_VIEW': (state, view) => {
    state.currentView = view;
  },
  'SET_CURRENT_CATEGORY_TYPE': (state, type) => {
    state.currentCategoryType = type;
  },
};

export default new Vuex.Store({
  state,
  getters,
  actions,
  mutations,
  modules: {
    MenuModule,
    UtilsModule
  }
});
