app.js:20  [Vue warn]: Template compilation error: Tags with side effect (<script> and <style>) are ignored in client component templates.
1  |  
2  |          <style>
   |          ^^^^^^^
3  |      :root {
   |  ^^^^^^^^^^^
4  |          --primary-dark: #242734;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
5  |          --secondary-gray: #4B5563;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
6  |          --tertiary-gray: #9CA3AF;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
7  |          --light-gray: #E5E7EB;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
8  |          --white: #F3F4F6;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^
9  |      }
   |  ^^^^^
10 |      
   |  ^^^^
11 |      body {
   |  ^^^^^^^^^^
12 |          font-family: 'Open Sans', sans-serif;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
13 |      }
   |  ^^^^^
14 |  </style>
   |  ^^^^^^^^ 
  at <App>
overrideMethod @ hook.js:608
warn$1 @ runtime-core.esm-bundler.js:51
onError @ vue.esm-bundler.js:62
ignoreSideEffectTags @ compiler-dom.esm-bundler.js:466
traverseNode @ compiler-core.esm-bundler.js:3195
traverseChildren @ compiler-core.esm-bundler.js:3187
traverseNode @ compiler-core.esm-bundler.js:3230
transform @ compiler-core.esm-bundler.js:3123
baseCompile @ compiler-core.esm-bundler.js:5776
compile2 @ compiler-dom.esm-bundler.js:666
compileToFunction @ vue.esm-bundler.js:54
finishComponentSetup @ runtime-core.esm-bundler.js:8070
setupStatefulComponent @ runtime-core.esm-bundler.js:8008
setupComponent @ runtime-core.esm-bundler.js:7933
mountComponent @ runtime-core.esm-bundler.js:5248
processComponent @ runtime-core.esm-bundler.js:5214
patch @ runtime-core.esm-bundler.js:4730
render2 @ runtime-core.esm-bundler.js:6040
mount @ runtime-core.esm-bundler.js:3963
app.mount @ runtime-dom.esm-bundler.js:1776
(anônimo) @ app.js:20
app.js:20  [Vue warn]: Template compilation error: Tags with side effect (<script> and <style>) are ignored in client component templates.
213|      <dashboard-manager></dashboard-manager>
214|  
215|      <script>
   |      ^^^^^^^^
216|      let searchTimeout;
   |  ^^^^^^^^^^^^^^^^^^^^^^
217|      
   |  ^^^^
218|      function toggleUserMenu() {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
219|          const menu = document.getElementById('userMenu');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
220|          menu.classList.toggle('hidden');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
221|      }
   |  ^^^^^
222|      
   |  ^^^^
223|      function openProject(projectId) {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
224|          // Redirect to editor with project ID
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
225|          window.location.href = `/editor?project=${projectId}`;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
226|      }
   |  ^^^^^
227|      
   |  ^^^^
228|      function createNewProject() {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
229|          // Open modal for new project
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
230|          document.getElementById('createProjectModal').classList.remove('hidden');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
231|          document.getElementById('projectTitle').focus();
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
232|      }
   |  ^^^^^
233|      
   |  ^^^^
234|      function closeCreateProjectModal() {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
235|          document.getElementById('createProjectModal').classList.add('hidden');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
236|          document.getElementById('createProjectForm').reset();
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
237|      }
   |  ^^^^^
238|      
   |  ^^^^
239|      async function handleCreateProject(event) {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
240|          event.preventDefault();
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
241|          
   |  ^^^^^^^^
242|          const form = event.target;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
243|          const formData = new FormData(form);
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
244|          const title = formData.get('title');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
245|          const description = formData.get('description');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
246|          
   |  ^^^^^^^^
247|          // Show loading state
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
248|          const btn = document.getElementById('createProjectBtn');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
249|          const btnText = document.getElementById('createProjectBtnText');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
250|          const spinner = document.getElementById('createProjectSpinner');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
251|          
   |  ^^^^^^^^
252|          btn.disabled = true;
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^
253|          btnText.textContent = 'Criando...';
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
254|          spinner.classList.remove('hidden');
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
255|          
   |  ^^^^^^^^
256|          try {
   |  ^^^^^^^^^^^^^
257|              // Debug: Verificar se o token CSRF está configurado
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
258|              console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.getAttribute('content'));
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
259|              console.log('Axios headers:', window.axios.defaults.headers.common);
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
260|              console.log('Dados enviados:', { title, description });
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
261|              
   |  ^^^^^^^^^^^^
262|              const response = await window.axios.post('/api/projects', {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
263|                  title: title,
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
264|                  description: description,
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
265|                  data: {
   |  ^^^^^^^^^^^^^^^^^^^^^^^
266|                      scenes: [],
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
267|                      settings: {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
268|                          canvas: {
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
269|                              width: 1920,
   |  ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
270 
  at <App>
overrideMethod @ hook.js:608
warn$1 @ runtime-core.esm-bundler.js:51
onError @ vue.esm-bundler.js:62
ignoreSideEffectTags @ compiler-dom.esm-bundler.js:466
traverseNode @ compiler-core.esm-bundler.js:3195
traverseChildren @ compiler-core.esm-bundler.js:3187
traverseNode @ compiler-core.esm-bundler.js:3230
transform @ compiler-core.esm-bundler.js:3123
baseCompile @ compiler-core.esm-bundler.js:5776
compile2 @ compiler-dom.esm-bundler.js:666
compileToFunction @ vue.esm-bundler.js:54
finishComponentSetup @ runtime-core.esm-bundler.js:8070
setupStatefulComponent @ runtime-core.esm-bundler.js:8008
setupComponent @ runtime-core.esm-bundler.js:7933
mountComponent @ runtime-core.esm-bundler.js:5248
processComponent @ runtime-core.esm-bundler.js:5214
patch @ runtime-core.esm-bundler.js:4730
render2 @ runtime-core.esm-bundler.js:6040
mount @ runtime-core.esm-bundler.js:3963
app.mount @ runtime-dom.esm-bundler.js:1776
(anônimo) @ app.js:20
dashboard:602  Uncaught TypeError: Cannot read properties of null (reading 'querySelector')
    at HTMLDocument.<anonymous> (dashboard:602:36)
(anônimo) @ dashboard:602
dashboard:602  Uncaught TypeError: Cannot read properties of null (reading 'querySelector')
    at HTMLDocument.<anonymous> (dashboard:602:36)
(anônimo) @ dashboard:602
DashboardManager.vue:135 CSRF Token: zFzJPPZxr8CJWuBVN8LRv4TQgAYF3LQeNFC00Og2
DashboardManager.vue:136 Axios headers: {Accept: 'application/json, text/plain, */*', Content-Type: undefined, X-Requested-With: 'XMLHttpRequest', X-CSRF-TOKEN: 'zFzJPPZxr8CJWuBVN8LRv4TQgAYF3LQeNFC00Og2'}
DashboardManager.vue:137 Dados enviados: {title: 'Teste 02', description: ''}
DashboardManager.vue:153 Resposta recebida: {success: true, project: {…}, id: 3}
DashboardManager.vue:162  Erro na requisição: TypeError: Cannot read properties of undefined (reading 'id')
    at Proxy.handleCreateProject (DashboardManager.vue:157:72)
overrideMethod @ hook.js:608
handleCreateProject @ DashboardManager.vue:162
await in handleCreateProject
(anônimo) @ DashboardManager.vue:15
cache.<computed>.cache.<computed> @ runtime-dom.esm-bundler.js:1714
callWithErrorHandling @ runtime-core.esm-bundler.js:199
callWithAsyncErrorHandling @ runtime-core.esm-bundler.js:206
invoker @ runtime-dom.esm-bundler.js:730
