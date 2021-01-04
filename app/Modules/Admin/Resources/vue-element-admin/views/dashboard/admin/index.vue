<template>
    <div class="dashboard-editor-container">
        <github-corner class="github-corner"/>

        <panel-group @handleSetLineChartData="handleSetLineChartData" :data="data"/>

        <el-row style="background:#fff;padding:16px 16px 0;margin-bottom:32px;">
            <line-chart :chart-data="lineChartData"/>
        </el-row>

        <el-row :gutter="32">
            <el-col :xs="24" :sm="24" :lg="10">
                <div class="chart-wrapper">
                    <box-card :skill="skill"/>
                </div>
            </el-col>
            <el-col :xs="24" :sm="24" :lg="14">
                <div class="chart-wrapper">
                    <bar-chart/>
                </div>
            </el-col>
        </el-row>

        <el-row :gutter="32">
            <el-col :xs="24" :sm="24" :lg="24">
                <div class="chart-wrapper chart-container">
                    <chart height="100%" width="100%" />
                </div>
            </el-col>
        </el-row>
    </div>
</template>

<script>
    import GithubCorner from '@/components/GithubCorner'
    import PanelGroup from './components/PanelGroup'
    import LineChart from './components/LineChart'
    import BarChart from './components/BarChart'
    import BoxCard from './components/BoxCard'
    import Chart from './components/LineMarker'
    import {statistics} from "@/api/indexs";

    const lineChartData = {
        newVisitis: {
            expectedData: [100, 120, 161, 134, 105, 160, 165],
            actualData: [120, 82, 91, 154, 162, 140, 145]
        },
        messages: {
            expectedData: [200, 192, 120, 144, 160, 130, 140],
            actualData: [180, 160, 151, 106, 145, 150, 130]
        },
        purchases: {
            expectedData: [80, 100, 121, 104, 105, 90, 100],
            actualData: [120, 90, 100, 138, 142, 130, 130]
        },
        four: {
            expectedData: [130, 140, 141, 142, 145, 150, 160],
            actualData: [120, 82, 91, 154, 162, 140, 130]
        }
    };

    export default {
        name: 'DashboardAdmin',
        components: {
            GithubCorner,
            PanelGroup,
            LineChart,
            BarChart,
            BoxCard,
            Chart
        },
        data() {
            return {
                lineChartData: lineChartData.newVisitis,
                skill:[], // 技能组
                data:{},
            }
        },
        created() {
            console.log('index-created');
            this.statistics();
        },
        methods: {
            async statistics() {
                this.listLoading = true;
                const {data} = await statistics();

                // console.log(data);
                this.data = data;
                // console.log(this.data);

                // 技能
                this.skill = data.skill;

                setTimeout(() => {
                    this.listLoading = false;
                }, 300);
            },
            handleSetLineChartData(type) {
                this.lineChartData = lineChartData[type];
            }
        }
    }
</script>

<style lang="scss" scoped>
    .dashboard-editor-container {
        padding: 32px;
        background-color: rgb(240, 242, 245);
        position: relative;

        .github-corner {
            position: absolute;
            top: 0px;
            border: 0;
            right: 0;
        }

        .chart-wrapper {
            background: #fff;
            padding: 16px 16px 0;
            margin-bottom: 32px;
        }

        .chart-container{
            position: relative;
            width: 100%;
            height: calc(100vh - 100px);
        }
    }

    @media (max-width: 1024px) {
        .chart-wrapper {
            padding: 8px;
        }
    }
</style>
