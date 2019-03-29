<template>
  <div class="stats">
    <span>
      <strong>Total expenses</strong>
    </span>
    <br>
    <br>

    <span>{{ expensesSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expected expenses</strong>
    </span>
    <br>
    <br>

    <span>{{ boundsSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expenses ratio</strong>
    </span>
    <br>
    <br>

    <span>{{ allTimeRatio }}</span>
    <br>
    <br>

    <span>
      <strong>Total revenues</strong>
    </span>
    <br>
    <br>

    <span>{{ revenuesSum }}</span>
    <br>
    <br>

    <span>
      <strong>Expected revenues</strong>
    </span>
    <br>
    <br>

    <span>{{ revenueBoundsSum }}</span>
    <br>
    <br>

    <span>
      <strong>Revenue ratio</strong>
    </span>
    <br>
    <br>

    <span>{{ revenueAllTimeRatio }}</span>
    <br>
    <br>

    <span>
      <strong>Total profit</strong>
    </span>
    <br>
    <br>

    <span>{{ totalProfit }}</span>
    <br>
    <br>

    <span>
      <strong>Average profit per month</strong>
    </span>
    <br>
    <br>

    <span>{{ averageProfitPerMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest profit month</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestProfitMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Highest profit month</strong>
    </span>
    <br>
    <br>

    <span>{{ highestProfitMonth }}</span>
    <br>
    <br>

    <span>
      <strong>Average profit per year</strong>
    </span>
    <br>
    <br>

    <span>{{ averageProfitPerYear }}</span>
    <br>
    <br>

    <span>
      <strong>Highest profit year</strong>
    </span>
    <br>
    <br>

    <span>{{ highestProfitYear }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest profit year</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestProfitYear }}</span>
    <br>
    <br>

    <span>
      <strong>Highest expense</strong>
    </span>
    <br>
    <br>

    <span>{{ highestExpense }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest expense</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestExpense }}</span>
    <br>
    <br>

    <span>
      <strong>Highest revenue</strong>
    </span>
    <br>
    <br>

    <span>{{ highestRevenue }}</span>
    <br>
    <br>

    <span>
      <strong>Lowest revenue</strong>
    </span>
    <br>
    <br>

    <span>{{ lowestRevenue }}</span>
    <br>
    <br>
    
    <router-link to="/visualisation">See graphics</router-link>

  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex';
import StatsMixin from '../mixins/stats.mixins.js';

export default {
  mixins: [StatsMixin]
};
</script>
