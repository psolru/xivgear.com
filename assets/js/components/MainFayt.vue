<template>
    <div class="searchbar w-100 position-relative mr-3" v-on:click.stop>
        <input v-model:value="q" class="form-control" placeholder="Search for a character…" type="text" @focus="setFocus" @keyup="requestRequest" @mouseup="requestRequest">
        <div class="results position-absolute shadow rounded pt-2 px-2 border border-primary bg-dark"
             v-show="showResultBar()">
            <a class="d-flex align-items-center mb-2" v-for="result in results" :key="result.id" v-bind:href="generateLink(result)">
                <img class="mr-2" :src="result.avatar">
                <div class="mr-2 server">{{ result.server }}</div>
                <div class="name">{{ result.name }}</div>
            </a>
            <i v-if="requesting" class="fas fa-lg fa-spinner fa-pulse"></i>
        </div>
    </div>
</template>

<script>
    export default {
        name: "mainFayt",
        data() {
            return {
                results: [],
                q: '',
                lastQ: '',
                requesting: false,
                requestTimeout: null,
                focused: false
            }
        },
        mounted() {
            window.addEventListener('click', () => {
                this.focused = false;
            });
        },
        methods: {
            generateLink: function(result) {
                return `/Character/${result.id}`;
            },
            setFocus: function() {
                this.focused = true;
            },
            showResultBar: function() {
                return (this.results.length || this.requesting) && this.focused;
            },
            requestRequest: function() {
                this.setFocus();

                if (!this.requesting && this.q && this.lastQ !== this.q) {
                    clearTimeout(this.requestTimeout);
                    this.requestTimeout = setTimeout(this.request,500);
                }
            },
            request: function() {
                this.requesting = true;
                this.lastQ = this.q;

                fetch(`https://xivapi.com/character/search?name=${this.q}`)
                    .then(response => response.json())
                    .then(json => {
                        this.results = [];
                        for (let key in json.Results) {
                            let result = json.Results[key];
                            this.results.push({
                                'id': result.ID,
                                'server': result.Server,
                                'name': result.Name,
                                'avatar': result.Avatar
                            })
                        }
                        this.requesting = false
                    });
            }
        }
    }
</script>

<style scoped lang="scss">
    @import "~bootstrap";
    .searchbar {
        max-width: 800px;
    }

    .searchbar input {

    }

    .searchbar input:focus {
        border-bottom-right-radius: 0;
        border-bottom-left-radius: 0;
    }

    .searchbar .results {
        width: 100%;
        border-top-right-radius: 0 !important;
        border-top-left-radius: 0 !important;
        border-top: 1px solid;
        max-height: 500px;
        overflow-y: scroll;
    }

    .searchbar .results a:hover {
        background-color: $primary;
        color: $white;
        text-decoration: none;
    }

    .searchbar .results a img {
        width: 50px;
    }

    .searchbar .results a .server {
        width: 120px;
    }
</style>
