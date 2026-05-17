// pipeline {
//     agent any

//         environment {
//         DOCKERHUB_USER = 'zeyadgamal'
//         IMAGE_TAG = "${DOCKERHUB_USER}/service-app:${BUILD_NUMBER}"
//     }

//     stages {

//         stage('Checkout') {
//             steps {
//                 checkout scmGit(
//                     branches: [[name: 'main']],
//                     userRemoteConfigs: [[
//                         url: 'https://github.com/Zeyad-Gamal/Jenkins-Final-Assignment.git',
//                         credentialsId: 'github-creds'
//                     ]]
//                 )
//             }
//         }

//         stage('SonarQube Analysis') {
//     steps {
//         withSonarQubeEnv('SonarQube-Server') {
//             sh """
//                 ${tool 'SonarScanner'}/bin/sonar-scanner \
//                 -Dsonar.projectKey=service-app \
//                 -Dsonar.sources=./src \
//                 -Dsonar.host.url=http://sonarqube:9000
//             """
//         }
//     }
// }



//         stage('Build Docker Image') {
//             steps {
//                 sh "docker build -t ${IMAGE_TAG} ."
//             }
//         }

//                 stage('Push to Docker Hub') {
//     steps {
//         withCredentials([usernamePassword(
//             credentialsId: 'dockerhub-creds',
//             usernameVariable: 'DOCKER_USER',
//             passwordVariable: 'DOCKER_PASS'
//         )]) {

//             sh '''
//                 echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin

//                 docker push ${IMAGE_TAG}
//             '''
//         }
//     }
// }

//         stage('Run Unit Tests') {
//             steps {
//                 catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
//                     sh '/usr/local/bin/phpunit --log-junit results.xml tests/'
//                 }
//             }
//         }

//         stage('Display Results') {
//             steps {
//                 junit 'results.xml'
//             }
//         }

//     }

//     post {
//         always {
//             echo 'Pipeline job finished.'
//         }
//         success {
//             echo 'Congratulations! All tests passed successfully.'
//         }
//         failure {
//             echo 'The code failed the tests! Please check the Test Result trend for details.'
//         }
//     }
// }






























pipeline {
    agent any

    environment {
        DOCKERHUB_USER = 'zeyadgamal'
        IMAGE_TAG = "${DOCKERHUB_USER}/service-app:${BUILD_NUMBER}"
    }

    stages {

        stage('Checkout') {
            steps {
                checkout scmGit(
                    branches: [[name: 'main']],
                    userRemoteConfigs: [[
                        url: 'https://github.com/Zeyad-Gamal/Jenkins-Final-Assignment.git',
                        credentialsId: 'github-pat-creds'
                    ]]
                )
            }
        }

        stage('SonarQube Analysis') {
            steps {
                withSonarQubeEnv('SonarQube-Server') {
                    sh """
                        ${tool 'SonarScanner'}/bin/sonar-scanner \
                        -Dsonar.projectKey=service-app \
                        -Dsonar.sources=./src \
                        -Dsonar.host.url=http://sonarqube:9000
                    """
                }
            }
        }

        stage('Build Docker Image') {
            steps {
                sh "docker build -t ${IMAGE_TAG} ."
            }
        }

        stage('Trivy Image Scan') {
            steps {
                sh """
                    trivy image \
                      --exit-code 1 \
                      --severity CRITICAL \
                      --no-progress \
                      ${IMAGE_TAG}
                """
            }
        }

        stage('Push to Docker Hub') {
            steps {
                withCredentials([usernamePassword(
                    credentialsId: 'dockerhub-creds',
                    usernameVariable: 'DOCKER_USER',
                    passwordVariable: 'DOCKER_PASS'
                )]) {

                    sh """
                        echo "$DOCKER_PASS" | docker login -u "$DOCKER_USER" --password-stdin
                        docker push ${IMAGE_TAG}
                    """
                }
            }
        }

        stage('Run Unit Tests') {
            steps {
                catchError(buildResult: 'SUCCESS', stageResult: 'FAILURE') {
                    sh '/usr/local/bin/phpunit --log-junit results.xml tests/'
                }
            }
        }

        stage('Display Results') {
            steps {
                junit 'results.xml'
            }
        }

        stage('Deploy') {
            steps {
                sh """
                    docker stop service-app || true
                    docker rm service-app || true

                    docker run -d \
                      --name service-app \
                      -p 8081:80 \
                      ${IMAGE_TAG}
                """
            }
        }
    }

    post {
        always {
            sh """
                docker rmi ${IMAGE_TAG} || true
                docker image prune -f
            """
        }

        success {
            echo 'Pipeline completed successfully.'
        }

        failure {
            echo 'Pipeline failed. Check logs.'
        }
    }
}
