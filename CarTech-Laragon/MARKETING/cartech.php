<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarTech - A Revolução da Gestão Mecânica</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
    </style>
</head>
<body class="bg-gray-950 text-white">

    <!-- Header -->
    <header class="bg-black shadow-lg sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-4 flex justify-between items-center">
            <!-- Logo e Nome da Empresa -->
            <div class="flex items-center space-x-2">
                <img src="https://raw.githubusercontent.com/Zelomacvini/cartech-projeto-uninove/main/images/logo-cartech.png" alt="Logo da CarTech" class="h-10">
            </div>

            <!-- Menu Desktop -->
            <div class="hidden md:flex items-center space-x-6">
                <a href="#inicio" class="text-gray-300 hover:text-white transition duration-300">Início</a>
                <a href="#beneficios" class="text-gray-300 hover:text-white transition duration-300">Benefícios</a>
                <a href="#contato" class="text-gray-300 hover:text-white transition duration-300">Contato</a>
                 <a href="/LOGIN/login.php" 
       class="px-5 py-2 bg-gray-700 text-white rounded-full font-semibold 
              hover:bg-white hover:text-gray-900 transition duration-300 shadow-md">
        Login
    </a>
            </div>

            <!-- Botão do Menu Mobile -->
            <button id="mobile-menu-button" class="md:hidden text-gray-400 focus:outline-none">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
        </nav>

        <!-- Menu Mobile (oculto por padrão) -->
        <div id="mobile-menu" class="hidden md:hidden bg-gray-900 p-4 shadow-lg transition-all duration-300">
            <a href="#inicio" class="block text-gray-300 hover:text-white py-2">Início</a>
            <a href="#beneficios" class="block text-gray-300 hover:text-white py-2">Benefícios</a>
            <a href="#contato" class="block text-gray-300 hover:text-white py-2">Contato</a>
            <a href="login.html" 
               class="block px-5 py-2 mt-4 bg-gray-700 text-white rounded-full text-center font-semibold 
                      hover:bg-white hover:text-gray-900 transition duration-300 shadow-md">
                Login
            </a>
        </div>
    </header>

    <!-- Hero Section -->
    <main id="inicio" class="py-20 md:py-32 text-center relative overflow-hidden">
        <div class="container mx-auto px-4 relative z-10">
            <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-white mb-4 animate-fade-in">
                O Fim da Bagunça na Oficina.<br>O Começo do Seu Crescimento.
            </h1>
            <p class="text-lg md:text-xl text-gray-300 max-w-2xl mx-auto mb-8 animate-fade-in delay-200">
                Chega de anotar serviços em papéis perdidos. CarTech é a plataforma completa para gerenciar ordens de serviço, clientes e equipes.
            </p>
            <a href="#contato" 
               class="px-8 py-3 bg-gray-700 text-white rounded-full font-bold text-lg 
                      hover:bg-white hover:text-gray-900 transition-colors duration-300 shadow-lg animate-fade-in delay-400">
                Conheça a CarTech
            </a>
        </div>
    </main>

    <!-- Seção de Benefícios -->
    <section id="beneficios" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Como a CarTech Transforma Sua Oficina</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <!-- Icone de Organização -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M15 14h.01" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Controle Total de Ordens de Serviço</h3>
                    <p class="text-gray-400 text-center">Gerencie cada etapa do serviço em um só lugar. Saiba exatamente o que está sendo feito e em que prazo, evitando atrasos.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <!-- Icone de Clientes -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Histórico de Clientes e Veículos</h3>
                    <p class="text-gray-400 text-center">Acesse o histórico de reparos com um clique. Melhore o atendimento ao cliente e ofereça serviços personalizados.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-800 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-700 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <!-- Icone de Comunicação -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Comunicação Clara e Ágil</h3>
                    <p class="text-gray-400 text-center">Reduza falhas de comunicação com sua equipe e clientes. O sistema centraliza todas as informações e atualizações.</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Seção para Inserir sua Imagem -->
    <section class="py-16 bg-gray-800">
        <div class="container mx-auto px-4 flex flex-col-reverse md:flex-row items-center justify-between">
            <!-- Conteúdo à Esquerda -->
            <div class="w-full md:w-1/2 text-center md:text-left mb-8 md:mb-0">
                <h2 class="text-2xl md:text-3xl font-bold text-white mb-2">Chegou a hora de deixar o papel de lado.</h2>
                <p class="text-gray-400 max-w-lg md:max-w-none">
                    Com a CarTech, você digitaliza toda a sua operação, do registro do veículo à comunicação com o cliente. Tenha um histórico completo de cada serviço, ganhe tempo e profissionalize sua oficina de uma vez por todas.
                </p>
            </div>
            <!-- Espaço para sua imagem (que agora usa a logo do seu repositório) -->
            <div class="w-full md:w-1/2 flex justify-center md:justify-end">
                <img src="https://raw.githubusercontent.com/Zelomacvini/cartech-projeto-uninove/main/images/logo-cartech.png" alt="Logo da CarTech" class="w-48 h-48">
            </div>
        </div>
    </section>

    <!-- Seção de CTA -->
    <section class="py-20 bg-gray-950 text-center">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Pronto para Otimizar sua Oficina?</h2>
            <p class="text-gray-400 mb-8 max-w-xl mx-auto">Junte-se a oficinas que já estão transformando sua gestão e oferecendo um serviço de excelência.</p>
            <a href="#contato" 
               class="px-8 py-4 bg-gray-700 text-white rounded-full font-bold text-lg 
                      hover:bg-white hover:text-gray-900 transition-colors duration-300 shadow-lg">
                Transforme sua Oficina. Entre em contato agora mesmo
            </a>
        </div>
    </section>

    <!-- Seção de Contato -->
    <section id="contato" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Entre em Contato</h2>
            <p class="text-gray-400 mb-8 max-w-2xl mx-auto">
                Tem alguma dúvida ou quer saber mais sobre como a CarTech pode ajudar sua oficina? 
                Envie-nos uma mensagem e nossa equipe entrará em contato o mais breve possível.
            </p>
            <!-- Formulário de Contato Placeholder -->
            <form class="space-y-6 max-w-lg mx-auto text-left">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-300">Nome</label>
                    <input type="text" id="nome" name="nome" required class="mt-1 block w-full px-4 py-2 bg-gray-950 border border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 text-white">
                </div>
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-300">E-mail</label>
                    <input type="email" id="email" name="email" required class="mt-1 block w-full px-4 py-2 bg-gray-950 border border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 text-white">
                </div>
                <div>
                    <label for="mensagem" class="block text-sm font-medium text-gray-300">Mensagem</label>
                    <textarea id="mensagem" name="mensagem" rows="4" required class="mt-1 block w-full px-4 py-2 bg-gray-950 border border-gray-700 rounded-md shadow-sm focus:outline-none focus:ring-gray-500 focus:border-gray-500 text-white"></textarea>
                </div>
                <button type="submit" 
                        class="w-full bg-gray-700 text-white font-bold py-3 px-4 rounded-full 
                               hover:bg-white hover:text-gray-900 transition-colors duration-300 shadow-md">
                    Enviar Mensagem
                </button>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-black py-8">
        <div class="container mx-auto px-4 text-center text-gray-400">
            <p>&copy; 2023 CarTech. Todos os direitos reservados.</p>
        </div>
    </footer>

    <!-- JavaScript para o menu mobile -->
    <script>
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');

        mobileMenuButton.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
        
        // Esconde o menu mobile ao clicar em um link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
            });
        });
    </script>
</body>
</html>