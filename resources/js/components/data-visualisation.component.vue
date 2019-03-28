<template>
  <div id="data-visualisation">
    <polar-area-chart :chart-data="datacollection"></polar-area-chart>
    <select v-model="expenseFilter">
      <option :value="'expense'">Expense</option>
      <option :value="'revenue'">Revenue</option>
    </select>
  </div>
</template>

<script>
import PolarAreaChart from "../charts/polar-area.chart.js";
import { mapGetters, mapActions } from "vuex";
export default {
  components: {
    PolarAreaChart
  },
  data() {
    return {
      datacollection: {},
      expenseFilter: "expense"
    };
  },
  mounted() {
    this.fillData();
  },
  computed: {
    ...mapGetters({
      budgets: "getBudgets",
      bounds: "getBounds",
      years: "getAllTimeYears"
    })
  },
  methods: {
    fillData() {
      this.datacollection = {
        labels: [],
        datasets: []
      };
      const boundsByCategory = this.bounds
        .filter(bound => {
          if (this.expenseFilter === "expense") {
            return bound.category.expense;
          }
          return !bound.category.expense;
        })
        .reduce((reduced, bound) => {
          if (!reduced[bound.category.name]) {
            reduced[bound.category.name] = [];
          }
          reduced[bound.category.name].push(bound);
          return reduced;
        }, {});

      const dataset = { backgroundColor: [], data: [] };

      Object.keys(boundsByCategory).forEach(category => {
        this.datacollection.labels.push(category);
        dataset.backgroundColor.push("rgba(77,55,99, 0.5)");
        dataset.data.push(
          boundsByCategory[category].reduce((sum, bound) => {
            sum += bound.expenses_sum;
            return sum;
          }, 0)
        );
      });

      this.datacollection.datasets.push(dataset);
    }
  },
  watch: {
    expenseFilter() {
      this.fillData();
    }
  }
};
</script>
