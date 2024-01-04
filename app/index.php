<html>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>

<head>
  <title>OwnWebYtDl</title>
</head>

<body>
  <div id="app">
    <h1>Own Youtube MP3 Downloader</h1>

    <div>
      <input type="text" v-model="url">
      <button @click="postData">Add to queue</button>
    </div>
    <div>
      <ul>
        <li v-for="item,key in data" :key="key">
          {{item.url}} - {{item.status}} <a v-if="item.file" :href="item.file">Download</a>
        </li>
      </ul>
    </div>
</body>

</html>


</div>

<script>
  const {
    createApp,
    ref,
    onMounted
  } = Vue

  createApp({
    setup() {
      const message = ref('Hello vue!');
      const url = ref('');
      const data = ref([]);

      function postData() {
        const data = new FormData();
        console.log(url.value);
        data.append('url', url.value);
        axios.post('/api.php', data).then(() => {
          url.value = '';
          getData();
        })
      }

      function getData() {
        axios.get('/api.php').then((response) => {
          data.value = response.data;
        })
      }
      onMounted(() => {
        getData();

        setInterval(() => {
          getData()
        }, 5000);
      })
      return {
        message,
        postData,
        url,
        data
      }
    }
  }).mount('#app')
</script>