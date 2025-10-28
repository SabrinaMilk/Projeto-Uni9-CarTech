<!DOCTYPE html>
<html lang="pt-BR" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CarTech - A Revolução da Gestão Mecânica</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        .gradient-text {
            background: linear-gradient(135deg, #ffffff 0%, #d1d5db 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
</head>
<body class="bg-gray-950 text-white">

  <header class="bg-black shadow-lg sticky top-0 z-50" style="height: 80px;">
    <nav class="container mx-auto px-4 h-16 flex justify-between items-center">
        <!-- Logo COM CROP -->
        <div class="flex items-center space-x-2 mt-4">
            <div style="width: 120px; height: 40px; display: flex; align-items: center; justify-content: center;">
                <img src="https://i.ibb.co/vC9Wg4ZP/1000271263-removebg-preview.png" 
                     alt="Logo da CarTech" 
                     style="height: 120px; width: auto; margin-top: -5px;">
            </div>
        </div>

        <!-- Menu Desktop -->
        <div class="hidden md:flex items-center space-x-6 mt-4  ">
            <a href="#inicio" class="text-gray-300 hover:text-white transition duration-300">Início</a>
            <a href="#sobre" class="text-gray-300 hover:text-white transition duration-300">Nossa História</a>
            <a href="#beneficios" class="text-gray-300 hover:text-white transition duration-300">Benefícios</a>
            <a href="#contato" class="text-gray-300 hover:text-white transition duration-300">Contato</a>
            <a href="LOGIN/login_empresa.php" 
               class="px-5 py-2 bg-gray-700 text-white rounded-full font-semibold 
                      hover:bg-white hover:text-gray-900 transition duration-300 shadow-md">
                Login
            </a>
        </div>

        <button id="mobile-menu-button" class="md:hidden text-gray-400 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
        </button>
    </nav>

    <div id="mobile-menu" class="hidden md:hidden bg-gray-900 p-4 shadow-lg transition-all duration-300">
        <a href="#inicio" class="block text-gray-300 hover:text-white py-2">Início</a>
        <a href="#sobre" class="block text-gray-300 hover:text-white py-2">Nossa História</a>
        <a href="#beneficios" class="block text-gray-300 hover:text-white py-2">Benefícios</a>
        <a href="#contato" class="block text-gray-300 hover:text-white py-2">Contato</a>
        <a href="LOGIN/login.php" 
           class="block px-5 py-2 mt-4 bg-gray-700 text-white rounded-full text-center font-semibold 
                  hover:bg-white hover:text-gray-900 transition duration-300 shadow-md">
            Login
        </a>
    </div>
</header>

<!-- Hero Section -->
<main id="inicio" class="min-h-screen relative overflow-hidden flex items-center justify-center">
    <!-- Imagem de fundo -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat"
         style="background-image: url('https://i.ibb.co/zWFty1Xf/Banner-1.png');"></div>
    
    <!-- Conteúdo com fundo semi-transparente -->
    <div class="relative z-10 w-full flex items-center justify-center">
        <div class="bg-black bg-opacity-60 rounded-3xl p-12 max-w-5xl mx-4">
            <div class="text-center">
                <h1 class="text-4xl md:text-6xl font-extrabold leading-tight text-white mb-6">
                    O fim da bagunça na oficina.<br>O começo do seu crescimento.
                </h1>
                <p class="text-lg md:text-xl text-gray-200 max-w-2xl mx-auto mb-8">
                    Chega de anotar serviços em papéis perdidos. CarTech é a plataforma completa para gerenciar ordens de serviço, clientes e equipes.
                </p>
                <a href="#contato" 
                   class="px-8 py-3 bg-white text-gray-900 rounded-full font-bold text-lg 
                          hover:bg-gray-200 hover:text-gray-800 transition-colors duration-300 shadow-lg">
                    Conheça a CarTech
                </a>
            </div>
        </div>
    </div>
</main>

    <!-- Seção Nossa História -->
    <section id="sobre" class="py-20 bg-gray-900">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold gradient-text mb-4">Da garagem ao sucesso: Nossa jornada</h2>
                <p class="text-xl text-gray-400 max-w-3xl mx-auto">
                    Conheça a história por trás da CarTech e como transformamos desafios em soluções inovadoras
                </p>
            </div>

            <!-- Timeline -->
            <div class="max-w-4xl mx-auto">
                <!-- 2010 - O Início -->
                <div class="flex flex-col md:flex-row items-center mb-16">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                            <span class="inline-block px-3 py-1 bg-yellow-500 text-black text-sm font-semibold rounded-full mb-4">2010</span>
                            <h3 class="text-2xl font-bold text-white mb-3">O desafio na prática</h3>
                            <p class="text-gray-300">
                                Tudo começou quando Marco Silva, amigo de João trabalhava como mecânico e via diariamente 
                                os problemas das oficinas: papéis perdidos, comunicação falha, clientes insatisfeitos. 
                                Ele anotava tudo em um caderno velho, sonhando com uma solução melhor.
                            </p>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1580273916550-e323be2ae537?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                             alt="Oficina antiga com papéis" 
                             class="w-full h-64 object-cover rounded-xl shadow-lg">
                    </div>
                </div>

                <!-- 2015 - A Ideia -->
                <div class="flex flex-col md:flex-row-reverse items-center mb-16">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pl-8">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                            <span class="inline-block px-3 py-1 bg-blue-500 text-white text-sm font-semibold rounded-full mb-4">2015</span>
                            <h3 class="text-2xl font-bold text-white mb-3">O estalo da inovação</h3>
                            <p class="text-gray-300">
                                Após perder um grande cliente por erro de comunicação, João se uniu a quatro amigos 
                                desenvolvedores. Juntos, em uma garagem adaptada, começaram a criar o primeiro protótipo 
                                do que seria a CarTech. Noites sem dormir, café e muita determinação.
                            </p>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1556761175-b413da4baf72?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                             alt time de desenvolvimento trabalhando" 
                             class="w-full h-64 object-cover rounded-xl shadow-lg">
                    </div>
                </div>

                <!-- 2018 - O Primeiro Cliente -->
                <div class="flex flex-col md:flex-row items-center mb-16">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pr-8">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                            <span class="inline-block px-3 py-1 bg-green-500 text-white text-sm font-semibold rounded-full mb-4">2018</span>
                            <h3 class="text-2xl font-bold text-white mb-3">A primeira vitória</h3>
                            <p class="text-gray-300">
                                A Oficina Master, desacreditada e prestes a fechar as portas, aceitou testar nosso sistema. 
                                Em 3 meses, a produtividade aumentou 40% e os clientes voltaram a confiar. Foi nossa prova 
                                de que estávamos no caminho certo.
                            </p>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1565688534245-05d6b5be184a?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                             alt="Oficina moderna organizada" 
                             class="w-full h-64 object-cover rounded-xl shadow-lg">
                    </div>
                </div>

                <!-- 2024 - Hoje -->
                <div class="flex flex-col md:flex-row-reverse items-center">
                    <div class="md:w-1/2 mb-6 md:mb-0 md:pl-8">
                        <div class="bg-gray-800 p-6 rounded-xl shadow-lg">
                            <span class="inline-block px-3 py-1 bg-purple-500 text-white text-sm font-semibold rounded-full mb-4">2024</span>
                            <h3 class="text-2xl font-bold text-white mb-3">Líderes no Mercado</h3>
                            <p class="text-gray-300">
                                Hoje, a CarTech é referência nacional em gestão para oficinas. Mais de 500 estabelecimentos 
                                confiam em nossa plataforma. Mas nossa missão continua a mesma: transformar a vida dos 
                                mecânicos através da tecnologia.
                            </p>
                        </div>
                    </div>
                    <div class="md:w-1/2">
                        <img src="https://images.unsplash.com/photo-1552664730-d307ca884978?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                             alt="Equipe CarTech moderna" 
                             class="w-full h-64 object-cover rounded-xl shadow-lg">
                    </div>
                </div>
            </div>

            <!-- Logo Grande no Final da História -->
            <div class="mt-16 text-center">
                <div class="max-w-md mx-auto">
                    <img src="https://i.ibb.co/vC9Wg4ZP/1000271263-removebg-preview.png" 
                         alt="Logo CarTech" 
                         class="w-full h-auto max-h-64 object-contain">
                </div>
            </div>

            <!-- Valores -->
            <div class="mt-20 grid md:grid-cols-3 gap-8 max-w-4xl mx-auto">
                <div class="text-center p-6 bg-gray-800 rounded-xl">
                    <div class="w-16 h-16 bg-yellow-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bullseye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Missão</h3>
                    <p class="text-gray-300">
                        Simplificar a gestão de oficinas através de tecnologia acessível e eficiente
                    </p>
                </div>
                <div class="text-center p-6 bg-gray-800 rounded-xl">
                    <div class="w-16 h-16 bg-blue-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-eye text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Visão</h3>
                    <p class="text-gray-300">
                        Ser referência nacional em soluções digitais para o setor automotivo
                    </p>
                </div>
                <div class="text-center p-6 bg-gray-800 rounded-xl">
                    <div class="w-16 h-16 bg-green-500 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-heart text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-3">Valores</h3>
                    <p class="text-gray-300">
                        Inovação, transparência, compromisso com o cliente e paixão por tecnologia
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Seção de Benefícios -->
    <section id="beneficios" class="py-20 bg-gray-800">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">Como a CarTech Transforma Sua Oficina</h2>
            <div class="grid md:grid-cols-3 gap-8">
                <!-- Card 1 -->
                <div class="bg-gray-700 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-clipboard-list text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Controle Total de OS</h3>
                    <p class="text-gray-400 text-center">Gerencie cada etapa do serviço em um só lugar. Saiba exatamente o que está sendo feito e em que prazo.</p>
                </div>

                <!-- Card 2 -->
                <div class="bg-gray-700 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Histórico Completo</h3>
                    <p class="text-gray-400 text-center">Acesse o histórico de reparos com um clique. Melhore o atendimento e ofereça serviços personalizados.</p>
                </div>

                <!-- Card 3 -->
                <div class="bg-gray-700 p-8 rounded-xl shadow-lg transform hover:scale-105 transition duration-300">
                    <div class="bg-gray-600 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-comments text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-center mb-3">Comunicação Ágil</h3>
                    <p class="text-gray-400 text-center">Reduza falhas de comunicação com sua equipe e clientes. Sistema centraliza todas as informações.</p>
                </div>
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

    <!-- Footer Melhorado -->
    <footer class="bg-black py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8 mb-8">
                <!-- Sobre a Empresa -->
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <img src="https://i.ibb.co/vC9Wg4ZP/1000271263-removebg-preview.png" alt="CarTech" class="h-14">
                        <span class="text-white font-semibold">CarTech</span>
                    </div>
                    <p class="text-gray-400 text-sm mb-4">
                        Líder em soluções de gestão para oficinas mecânicas, transformando negócios através da tecnologia desde 2015.
                    </p>
                    <div class="flex space-x-3">
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition duration-300">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Links Úteis -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Links Úteis</h3>
                    <ul class="space-y-2">
                        <li><a href="#inicio" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Início</a></li>
                        <li><a href="#sobre" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Nossa História</a></li>
                        <li><a href="#beneficios" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Benefícios</a></li>
                        <li><a href="#contato" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Contato</a></li>
                        <li><a href=".../LOGIN/login_empresa.php" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Área do Cliente</a></li>
                    </ul>
                </div>

                <!-- Suporte -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Suporte</h3>
                    <ul class="space-y-2">
                        <li class="text-gray-400 text-sm">📞 (11) 9999-9999</li>
                        <li class="text-gray-400 text-sm">✉️ suporte@cartech.com</li>
                        <li class="text-gray-400 text-sm">🕒 Seg-Sex: 8h-18h</li>
                        <li class="text-gray-400 text-sm">📍 São Paulo - SP</li>
                    </ul>
                    <div class="mt-4">
                       <a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">
    📋 Manual do Usuário
</a>
                    </div>
                </div>

                <!-- Empresa -->
                <div>
                    <h3 class="text-lg font-semibold text-white mb-4">Empresa</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Trabalhe Conosco</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Política de Privacidade</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Termos de Uso</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Blog</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition duration-300 text-sm block">Imprensa</a></li>
                    </ul>
                </div>
            </div>

            <!-- Fundadores -->
            <div class="border-t border-gray-800 pt-8 mb-8">
                <h3 class="text-lg font-semibold text-white mb-6 text-center">Fundadores</h3>
                <div class="grid grid-cols-2 md:grid-cols-5 gap-4 max-w-4xl mx-auto">
                    <!-- João Pedro -->
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-yellow-500 to-amber-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-sm">JP</span>
                        </div>
                        <h4 class="text-white font-semibold text-sm">João Pedro</h4>
                        <p class="text-gray-400 text-xs">GP & ES & Fundador</p>
                    </div>
                    
                    <!-- Arthur Dourado -->
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-cyan-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-sm">AD</span>
                        </div>
                        <h4 class="text-white font-semibold text-sm">Arthur Dourado</h4>
                        <p class="text-gray-400 text-xs">ES & Co-fundador</p>
                    </div>
                    
                    <!-- Giovanna Antonucci -->
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-sm">GA</span>
                        </div>
                        <h4 class="text-white font-semibold text-sm">Giovanna Antonucci</h4>
                        <p class="text-gray-400 text-xs">ES & Co-fundadora</p>
                    </div>
                    
                    <!-- Micael Soares -->
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-red-500 to-orange-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-sm">MS</span>
                        </div>
                        <h4 class="text-white font-semibold text-sm">Micael Soares</h4>
                        <p class="text-gray-400 text-xs">ES & Co-fundador</p>
                    </div>
                    
                    <!-- Sabrina Souza -->
                    <div class="text-center">
                        <div class="w-14 h-14 bg-gradient-to-r from-green-500 to-emerald-500 rounded-full flex items-center justify-center mx-auto mb-2">
                            <span class="text-white font-bold text-sm">SS</span>
                        </div>
                        <h4 class="text-white font-semibold text-sm">Sabrina Souza</h4>
                        <p class="text-gray-400 text-xs">ES & Co-fundadora</p>
                    </div>
                </div>
            </div>

            <!-- Área Admin Oculto -->
            <div class="border-t border-gray-800 pt-6 text-center">
                <a href="LOGIN/login_admin.php?tipo=adm" 
                   class="text-xs text-gray-600 hover:text-gray-400 transition duration-300 inline-flex items-center">
                    <i class="fas fa-cog mr-1"></i>
                    Área Administrativa
                </a>
            </div>

            <!-- Copyright -->
            <div class="border-t border-gray-800 pt-6 text-center">
                <p class="text-gray-400 text-sm">
                    &copy; 2024 CarTech Sistemas Ltda. - CNPJ: 12.345.678/0001-90 - Todos os direitos reservados.
                </p>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Menu Mobile
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

        // Smooth Scroll para links internos
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>