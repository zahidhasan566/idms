<script>
import {Bar} from "vue-chartjs";

export default {
  props: ["labels", "datasets"],
  extends: Bar,
  data: () => ({
    chartdata: {},
    options: {
      scales: {
        size: 250,
        xAxes: [
          {
            stacked: true,
            stepSize: 5,
            maxTicksLimit: 5,
            // gridLines: { display: true }
          },
        ],
        yAxes: [
          {
            stacked: true,
            stepSize: 5,
            maxTicksLimit: 5,
          },
        ],
      },
      responsive: true,
      maintainAspectRatio: false,
      legend: {display: true},
    },
  }),

  mounted() {
    // this.datasets = [
    //   {
    //     label: 'Target',
    //     backgroundColor: ['#6488EA','#6488EA','#6488EA','#6488EA','#6488EA','#cb2d2d'],
    //     borderColor: '#6488EA',
    //     borderWidth: 1,
    //     data: [100,200,50,60,11,99],
    //   }
    // ]
    console.log(this.datasets,'charts call')
    this.chartdata.labels = this.labels;
    this.chartdata.datasets = this.datasets;
    this.renderChart(this.chartdata, this.options);
  },
};
</script>
<style lang="scss">
</style>
