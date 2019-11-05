<template>
    <div>
        <div class="container-fluid sticky-top d-block bg-white mb-5 shadow-lg">
            <div class="row">
                <div class="col-12">
                    <div class="sticky-top d-block bg-white mb-5">
                        <h1 class="text-center mt-5">Link checker</h1>
                        <div class="steps col-10 offset-1">
                            <div class="progress-container">
                                <ol :class="this.gs_fetched ? 'progression-bar step-2': this.checking_complete ? 'progression-bar step-3':'progression-bar'">
                                    <li class="is-active"><span class="progression-title">Google sheets</span><span class="progression-date"></span></li>
                                    <li :class="this.gs_fetched ? 'is-active':''"><span class="progression-title">Checking</span><span class="progression-date"></span></li>
                                    <li :class="this.checking_complete ? 'is-active':''"><span class="progression-title">Done</span><span class="progression-date"></span></li>
                                </ol></div>
                        </div>
                        <div class="progress mt-5 mb-5">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuemin="0" aria-valuemax="100" :style="'width:' + this.progress +'%'">
                                {{ this.checked.length }} from {{ this.sites.length }} checked
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-12">

                    <ul class="list-unstyled">
                        <li v-for="site in checked" class="mb-3">
                            <div class="card card-body shadow-sm">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>{{ site.uri }}</h5>
                                    <span class="badge badge-default"><strong>Transfer time: </strong>{{ site.transfer_time }} ms</span>
                                    <span :class="site.status === 200 ? 'badge badge-success':'badge badge-danger'">{{ site.status }}</span>
                                </div>
                                <p class="small">responded with {{ site.status }}</p>
                                <p class="alert alert-danger" v-if="site.status != 200">{{ site.message }}</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import $ from 'jquery'
    export default {
        data(){
            return {
                gs_fetched: false,
                checking_complete:false,
                sites:[],
                currentSite:0,
                maxSite:3,
                checked:[],
                progress:0
            }
        },
        watch:{
            'checked'(){
                this.progress = this.checked.length / this.sites.length * 100
            }
        },
        methods:{
            getStartData(){
                axios.get('google.php').then((res)=>{
                    this.sites = res.data
                    this.gs_fetched = true
                    this.fetchSites()
                })
            },
            fetchSites(){
                let all = []
                for (let step = this.currentSite; step < this.maxSite; step+=1) {
                    all.push(this.getSingleSite("http://" + this.sites[step]['url']))
                }
                axios.all(all).then(axios.spread((...responses) => {
                    this.currentSite += 3
                    this.maxSite += 3
                    responses.forEach(r => {
                        this.checked.push(r.data)
                    })

                    if(this.currentSite < this.sites.length) {

                            this.fetchSites()
                        if(this.currentSite >= this.sites.length) {
                            this.checking_complete = true
                        }
                    }
                })).catch(errors => {

                })
            },
            getSingleSite(url){
                return axios({
                    method: 'get',
                    url: 'curl.php?link=' + url
                })
            }
        },
        mounted(){
            this.getStartData()
        }
    }
</script>

<style scoped lang="scss">
    // Brand Colors
    $brand-orange:          #F85F23; // orange
    $brand-light-orange:    #FB9F7B;
    $brand-blue:            #103060; // blue
    $brand-light-blue:      #0559b3; // light blue
    $brand-lighter-blue:    #508ac9; // lighter blue
    $brand-lightest-blue:   #739DD0; // lightest blue (60% opacity)
    $brand-pale-blue:       #eef7ff; // pale blue
    $brand-yellow:          #ffffe7; // yellow
    $brand-red:             #cf0000; // red
    $brand-light-red:       #E26666;
    $brand-green:           #77b800; // Grreen
    $error-color:           #d0021b; // error red

    // Brand Greys
    $brand-rich-gray:       #404040; // rich gray
    $brand-darker-gray:     #545554;
    $brand-med-gray:        #808080; // med gray
    $brand-light-gray:      #bfbfbf; // light gray
    $brand-lighter-gray:    #e5e5e5; // lighter gray
    $brand-lightest-gray:   #f5f5f5; // lightest gray
    $brand-pinkish-gray:    #c5c5c5; // pinkish gray
    $black:                 black; // black
    $white:                 white; // white

    //## Gray and brand colors for use across Bootstrap.

    $gray-base:              $brand-rich-gray;
    $gray-darker:            lighten($gray-base, 13.5%);
    $gray-dark:              $brand-med-gray;
    $gray:                   $brand-light-gray;
    $gray-light:             $brand-lighter-gray;
    $gray-lighter:           lighten($gray-base, 93.5%);


    // Overide w/ Brand colors to brand full theme
    $brand-primary:         $brand-blue;
    $brand-success:         $brand-green;
    $brand-success-hover:   #B3D36E;
    $brand-info:            $brand-light-blue;
    $brand-warning:         $brand-orange;
    $brand-danger:          $brand-red;





    .progress-container {
        padding-top: 30px;
        padding-right: 43px;
    }
    /*Progress steps styles*/
    .progression-bar {
        list-style: none;
        margin:  0 0 38px 0;
        padding: 0;
        display: flex;
        width: 100%;
        z-index: 1;

        position: relative;
        flex-direction: row;
        align-items: stretch;
        justify-content: space-between;

        &:before {
            transition-delay: 2s;
            transition: width .5s linear;
            content: "";
            width: 20px;
            background-color: $brand-green;
            height: 8px;
            left:20px;
            position: absolute;
            top: 47%;
            transform: translateY(-47%);
            z-index: 2;


        }

        &:after {
            transition: width .9s ease-in-out;
            content: "";
            width: 98%;
            background-color: $brand-lighter-gray;
            height: 8px;
            left: 9px;
            position: absolute;
            top: 47%;
            will-change: transform;
            transform: translateY(-47%);
            z-index: 1;

        }
        /*this classes are added to animate the progress bar*/

        /*this classes are added to animate the progress bar*/
        &.step-2,&.pre-pending {
            &:before {
                width: 48%;
            }
        }
        &.step-3,
        &.interview-complete{
            &:before {
                width: 100%;
            }
        }
        &.step-4{
            &:before {
                width: 74.5%;
            }
        }
        &.step-5,
        &.post-sent{
            &:before {
                width: 98%;
            }
        }

        li{
            display: flex;
            position: relative;
            height: 50px;

            cursor: pointer;
            flex-basis: 38px;

            &:before {
                content:"";
                z-index: 6;
                background-color: $white;
                position: absolute;
                top: 50%;
                left: 0;
                will-change: transform;
                transform: translateY(-50%);
                text-align: center;
                display: block;
                width: 38px;
                height: 38px;
                border-radius: 50%;
                border: 5px solid $brand-lighter-gray;
                font-size: 18px;
                line-height: 28px;
                transition: all .5s linear;

            }
            &:after {
                will-change: opacity;
                transition: opacity .5s linear;
                opacity:0;
                content: "";
                display: block;
            }
            &.is-pending:before {
                border-color: #e8f22e;
            }

            &:last-child {
                margin: 0;
                background-color: $white;

            }
            &.is-active,
            &:hover{
                color: $brand-green;
                position: relative;
                &:before {
                    box-shadow: 0 0 6px 1px #cccccc;
                    border-color: $brand-green;
                    content: "";
                    text-align: center;
                }
                &:after {
                    content: "";
                    opacity:1;
                    display: block;
                    width: 9px;
                    height: 18px;
                    border: solid $brand-green;
                    border-width: 0 4px 4px 0;
                    transform: rotate(45deg);
                    z-index: 6;
                    position: absolute;
                    top: 14px;
                    left: 14px;
                }
            }
            &.is-active {
                &:before {
                    box-shadow: none;
                }
            }

            .progression-title,
            .progression-date  {
                color:#656565;
                position: absolute;
                white-space: nowrap;
            }
            .progression-title {
                top: -24px;
                left: 50%;
                transform: translateX(-50%);

            }
            .progression-date {
                bottom: -24px;
                left: 50%;
                transform: translateX(-50%);
            }
        }
    }
</style>