import Vue from 'vue';
import Vuex from 'vuex';
Vue.use(Vuex);

import MenuModule from './modules/menu.module';

const user = handover && handover.user ? handover.user : [];
const bounds = handover && handover.bounds ? handover.bounds : [];

window.mobilePlatform = () => {
  var check = false;
  (function (a) { if (/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a) || /1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0, 4))) check = true; })(navigator.userAgent || navigator.vendor || window.opera);
  return check;
};

const state = {
  mobilePlatform: window.mobilePlatform(),
  currentView: 'grid-month',
  currentCategoryType: 'expense',
  user: user,
  bounds: bounds,
  selectedCategory: undefined,
  showDisplayPanel: false,
  selectedYear: `${new Date(Date.now()).getFullYear()}`,
  selectedMonth: `0${(new Date(Date.now()).getMonth() + 1)}`,
  justUpdated: undefined,
};
const getters = {
  isMobilePlatform: state => state.mobilePlatform,
  getUser: state => state.user,
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
  getSelectedCategoryId: state => state.selectedCategory,
  getSelectedCategory: state => state.bounds.find(c => c.id === state.selectedCategory),
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
  addCategory: ({ commit }, category) => {
    commit('ADD_CATEGORY', category);
  },
  selectCategory: ({ commit, state }, categoryId) => {
    commit('SELECT_CATEGORY', categoryId);
  },
  setJustUpdated: ({ commit }, identifier) => {
    commit('SET_JUST_UPDATED', identifier);
  },
  setShowDisplayPanel: ({ commit }, value) => {
    commit('SET_SHOW_DISPLAY_PANEL', value);
  },
  addExpense: ({ commit }, expenseData) => {
    axios.post('/add-expense', expenseData)
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
  addBounds: ({ commit }, bounds) => {
    commit('ADD_BOUNDS', bounds);
  },
  deleteBounds: ({ commit }, budgetId) => {
    commit('DELETE_BOUNDS', budgetId);
  },
  updateCategoryBound: ({ commit }, data) => {
    const bound = state.bounds.find(c => c.id === data.categoryId);

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
  'SELECT_CATEGORY': (state, categoryId) => {
    state.selectedCategory = categoryId;
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
      bound.expenses = [
        ...(bound.expenses ? bound.expenses : []),
        data
      ]
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
    const bound = state.bounds.find(c => c.id === data.categoryId);

    if (bound) {
      bound.bound_in_cents = data.value;
    }
  },
  'ADD_BOUNDS': (state, bounds) => {
    bounds.forEach(bound => {
      state.bounds.push(bound);
    });
  },
  'DELETE_BOUNDS': (state, budgetId) => {

    const indexesToRemove = state.bounds.map((b, i) => {
      if (b.budget_id === budgetId) {
        return i;
      }
      return false;
    })
      .filter(i => i);

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
    MenuModule
  }
});
