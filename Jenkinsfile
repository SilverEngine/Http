pipeline {
  agent any
  stages {
    stage('Deploy') {
      steps {
        echo 'Deploy done'
      }
    }
    stage('Junit') {
      steps {
        echo 'Junit done'
      }
    }
    stage('Chrome test') {
      parallel {
        stage('Chrome test') {
          steps {
            echo 'Chrome done'
          }
        }
        stage('Firefox test') {
          steps {
            echo 'Firefox done'
          }
        }
        stage('IE+') {
          steps {
            echo 'IE done'
          }
        }
      }
    }
    stage('Build docker') {
      parallel {
        stage('Build docker') {
          steps {
            echo 'Docker done'
          }
        }
        stage('Validate build') {
          steps {
            echo 'Success'
          }
        }
      }
    }
    stage('Unit test') {
      steps {
        echo 'Complete'
      }
    }
    stage('Deploy dev') {
      parallel {
        stage('Deploy dev') {
          steps {
            echo 'Done'
          }
        }
        stage('Deploy Stag') {
          steps {
            echo 'Done'
          }
        }
      }
    }
    stage('Test Stag On Prod') {
      steps {
        echo 'Done'
      }
    }
    stage('Deploy on PROD') {
      steps {
        echo 'Success '
      }
    }
  }
}