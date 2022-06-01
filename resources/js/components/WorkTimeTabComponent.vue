<template>
  <div class="panel-body">
    <div class="project-table-wrapper">
      <table class="table table-bordered table-project">
        <thead>
          <tr>
            <th class="table-project-day">{{ __('work_time_type') }}</th>
            <th
              colspan="2"
              class="table-project"
              v-for="user in allUsersWithLoggedHours"
              :key="user.id"
            >
              {{ user.name }}
            </th>
            <th colspan="1" class="table-project-hours-total text-center">
              {{ __('houres') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(workTime, key, index) in allLoggedWorkTimes" :key="index">
            <td>{{ key }}</td>
            <fragment v-for="(hours, index) in workTime" :key="index">
              <td class="table-project-hours">{{ hours.hours }}</td>
              <td class="table-project-tasks" v-if="hours.colour != null">
                <span
                  v-if="hours.hours > 0"
                  class="label"
                  style="color: white"
                  :style="{ 'background-color': hours.colour }"
                  >{{ key }}</span
                >
              </td>
            </fragment>
          </tr>
        </tbody>
        <thead>
          <tr>
            <th class="table-project-day">{{ __('summary') }}</th>
            <fragment
              v-for="(user, index) in allUsersWithLoggedHours"
              :key="index"
            >
              <th class="text-center">{{ user.hours }}</th>
              <th class="table-project-total"></th>
            </fragment>
            <th class="text-center">
              <h3>{{ allLoggedHours }}</h3>
            </th>
          </tr>
        </thead>
      </table>
    </div>

    <hr />

    <div class="row m-b">
      <div class="col-3">
        <button
          class="btn btn-primary btn-xs"
          @click="getWorkTimesPerMonth(month - 1, year)"
        >
          <i class="fas fa-arrow-left"></i> {{ __('month_before') }}
        </button>
      </div>
      <div class="col-6 text-center">
        <h3>{{ monthPL }} {{ year }}</h3>
      </div>
      <div class="col-3 text-right">
        <button
          class="btn btn-primary btn-xs"
          @click="getWorkTimesPerMonth(month + 1, year)"
        >
          {{ __('next_month') }} <i class="fas fa-arrow-right"></i>
        </button>
      </div>
    </div>

    <div class="project-table-wrapper">
      <table class="table table-bordered table-project table-project-card">
        <thead>
          <tr>
            <th class="table-project-day">{{ __('day_of_the_month') }}</th>
            <th
              colspan="2"
              class="table-project"
              v-for="user in monthAllUsers"
              :key="user.id"
            >
              {{ user.name }}
            </th>
            <th colspan="1" class="table-project-hours-total text-center">
              {{ __('houres') }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="(day, key) in workTimesDays" :key="key">
            <td>{{ key }}</td>
            <template v-for="(workTime, index) in day.dayWorkTimes">
              <td class="table-project-hours" :key="index">
                {{ workTime.hours }}
              </td>
              <td class="table-project-tasks" :key="'workTimeID' + index">
                <fragment
                  v-for="(task, index) in workTime.dayWorkTimes"
                  :key="index"
                >
                  <span
                    class="label"
                    style="color: white"
                    :style="{ 'background-color': task.work_time_type.colour }"
                    >{{ task.task }} ({{ parseFloat(task.logged_hours) }})</span
                  ><br />
                </fragment>
              </td>
            </template>
            <td class="text-center">{{ day.dayHours }}</td>
          </tr>
        </tbody>
        <thead>
          <tr>
            <th class="table-project-day">{{ __('summary') }}</th>
            <template v-for="user in monthAllUsers">
              <th class="text-center" :key="user.id">{{ user.hours }}</th>
              <th class="table-project-total" :key="user.name + user.id">
                <span
                  v-for="(workTime, key, index) in user.workTimeTypes"
                  :key="index"
                  class="m-0 label mr-1"
                  style="color: white"
                  :style="{ 'background-color': workTime.colour }"
                >
                  {{ key }} ({{ parseFloat(workTime.hours) }})
                </span>
              </th>
            </template>
            <th class="text-center">
              <h3>{{ monthAllHours }}</h3>
            </th>
          </tr>
        </thead>
      </table>
    </div>
  </div>
</template>


<script>
import { Fragment } from "vue-fragment";

export default {
  name: "WorkTimeTab",
  props: ["projectId"],
  components: {
    Fragment,
  },
  data() {
    return {
      month: "",
      monthPL: "",
      year: "",
      monthsNames: [],
      allLoggedWorkTimes: null,
      allUsersWithLoggedHours: null,
      allLoggedHours: 0,
      workTimesDays: null,
      monthAllUsers: null,
      monthAllHours: 0,
    };
  },
  mounted() {
    let today = new Date();
    this.month = today.getUTCMonth() + 1;
    this.monthPL = this.monthsNames[today.getUTCMonth()];
    this.year = today.getUTCFullYear();

    this.getAllWorkTimes();
    this.getWorkTimesPerMonth(this.month, this.year);
    this.monthsNames = this._months();
  },
  methods: {
    async getAllWorkTimes() {
      await axios
        .post("/api/get/all-work-times", {
          projectId: this.projectId,
        })
        .then((response) => {
          this.allLoggedWorkTimes = response.data.allWorkTimes;
          this.allUsersWithLoggedHours = response.data.users;
          this.allLoggedHours = response.data.allLoggedHours;
        });
    },
    async getWorkTimesPerMonth(month, year) {
      await axios
        .post("/api/get/work-times-month", {
          projectId: this.projectId,
          year: year,
          month: month,
        })
        .then((response) => {
          this.month = response.data.month;
          this.year = response.data.year;
          this.monthPL = this.monthsNames[this.month - 1];
          this.$set(this, "monthAllHours", response.data.monthAllHours);
          this.$set(this, "monthAllUsers", response.data.monthAllUsers);
          this.$set(this, "workTimesDays", response.data.workTimesDays);
        });
    },
  },
};
</script>
