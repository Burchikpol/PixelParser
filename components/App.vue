<template>
    <div>
        <ul>
            <li v-for="site in checked">{{ site.url }} responded with {{ site.status }}</li>
        </ul>
    </div>
</template>

<script>
    import axios from 'axios'
    export default {
        data(){
            return {
                sites:[],
                currentSite:0,
                maxSite:10,
                checked:[]
            }
        },
        methods:{
            getStartData(){
                axios.get('google.php').then((res)=>{
                    this.sites = res.data
                    this.fetchSites()
                })
            },
            fetchSites(){
                let all = []
                this.sites.forEach((site)=>{
                    axios.get('curl.php?link=' + site['url']).then((res)=>{
                        this.checked.push(res.data)
                    })
                    this.currentSite += 1
                })
                // for (let step = 0; step < this.maxSite; step+=1) {
                //     all.push(this.getSingleSite("http://" + this.sites[step]['url']))
                // }
                // axios.all(all).then((res)=>{
                //     this.checked = res
                //     this.currentSite += this.maxSite
                //     this.maxSite += 10
                //     if(this.currentSite < this.sites.length) {
                //     // if(this.currentSite < 30) {
                //         this.fetchSites()
                //     }
                // })
            },
            getSingleSite(url){
                return axios.get({
                    method: 'get',
                    url: 'curl.php?link=' + url
                }).then((res)=>{
                    this.checked.push(res.data)
                })
            }
        },
        mounted(){
            this.getStartData()
        }
    }
</script>