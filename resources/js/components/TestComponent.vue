<template>
    <div class="test-component">
        <h2>{{ title }}</h2>
        <p>{{ message }}</p>
        <button @click="incrementCounter" class="btn btn-primary">
            Clicado {{ counter }} vezes
        </button>
        <div v-if="counter > 0" class="mt-3">
            <p class="text-success">Vue.js está funcionando perfeitamente! 🎉</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TestComponent',
    data() {
        return {
            title: 'Componente de Teste Vue.js',
            message: 'Este é um componente Vue.js funcionando no Laravel!',
            counter: 0
        }
    },
    methods: {
        incrementCounter() {
            this.counter++;
        }
    },
    mounted() {
        console.log('TestComponent montado com sucesso!');
    }
}
</script>

<style scoped>
.test-component {
    padding: 20px;
    border: 2px solid #42b883;
    border-radius: 8px;
    background-color: #f9f9f9;
    margin: 20px;
}

.test-component h2 {
    color: #42b883;
    margin-bottom: 15px;
}

.test-component p {
    color: #333;
    margin-bottom: 15px;
}

.btn {
    padding: 10px 20px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-primary:hover {
    background-color: #0056b3;
}

.text-success {
    color: #28a745;
    font-weight: bold;
}

.mt-3 {
    margin-top: 1rem;
}
</style>